<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase as BaseTestCase;

final class ActivityLogsTest extends BaseTestCase
{
    use DatabaseTransactions;

    protected function admin(): User
    {
        return User::where('role', 'admin')->firstOrFail();
    }

    protected function guest(): User
    {
        return User::where('role', 'guest')->firstOrFail();
    }

    public function test_login_is_logged(): void
    {
        $guest = $this->guest();

        $this
            ->withSession(['_token' => 't'])
            ->post('/login', ['_token' => 't', 'email' => $guest->email, 'password' => 'password'])
            ->assertRedirect();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'auth.login',
            'user_id' => $guest->id,
        ]);
    }

    public function test_admin_login_uses_distinct_event(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $this
            ->withSession(['_token' => 't'])
            ->post('/login', ['_token' => 't', 'email' => $admin->email, 'password' => 'password'])
            ->assertRedirect();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'auth.admin_login',
            'user_id' => $admin->id,
        ]);
    }

    public function test_booking_creation_is_logged(): void
    {
        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'message' => 'Authorization URL created',
                'data' => ['authorization_url' => 'https://checkout.paystack.com/log1', 'access_code' => 'x', 'reference' => 'BOOK-LOG1'],
            ], 200),
        ]);

        $room = Room::where('is_available', true)->firstOrFail();

        $this
            ->withSession(['_token' => 't'])
            ->post(route('booking.store'), [
                '_token' => 't',
                'room_id' => $room->id,
                'check_in' => now()->addDays(50)->toDateString(),
                'check_out' => now()->addDays(52)->toDateString(),
                'guests_count' => 1,
                'guest_name' => 'Log Guest',
                'guest_email' => 'log'.Str::random(6).'@example.com',
            ])
            ->assertRedirect('https://checkout.paystack.com/log1');

        $this->assertDatabaseHas('activity_logs', ['action' => 'booking.created']);
    }

    public function test_admin_booking_update_is_logged_with_transition(): void
    {
        $booking = Booking::create([
            'user_id' => $this->guest()->id,
            'room_id' => Room::where('is_available', true)->firstOrFail()->id,
            'check_in' => now()->addDays(60)->toDateString(),
            'check_out' => now()->addDays(62)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'pending_payment',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        $this->actingAs($this->admin())
            ->withSession(['_token' => 't'])
            ->put(route('admin.bookings.update', $booking), ['_token' => 't', 'status' => 'cancelled'])
            ->assertRedirect();

        $log = ActivityLog::where('action', 'admin.booking_updated')->latest()->first();
        $this->assertNotNull($log);
        $this->assertSame('pending_payment', $log->properties['from']);
        $this->assertSame('cancelled', $log->properties['to']);
        $this->assertSame($this->admin()->id, $log->user_id);
    }

    public function test_logs_page_renders_and_filters(): void
    {
        ActivityLogger::log('booking.created', null, [], 'Test event one');
        ActivityLogger::log('auth.login', $this->admin(), [], 'Test event two');

        $this->actingAs($this->admin())
            ->get(route('admin.activity-logs.index'))
            ->assertOk()
            ->assertSee('Test event one', escape: false);

        $this->actingAs($this->admin())
            ->get(route('admin.activity-logs.index', ['action' => 'auth.login']))
            ->assertOk()
            ->assertSee('Test event two', escape: false)
            ->assertDontSee('Test event one', escape: false);
    }

    public function test_logs_page_forbidden_for_guests(): void
    {
        $this->actingAs($this->guest())
            ->get(route('admin.activity-logs.index'))
            ->assertForbidden();
    }

    public function test_logger_never_throws(): void
    {
        // Garbage subject state must not break the caller.
        ActivityLogger::log('test.event', null, ['nested' => ['a' => 1]], 'safe');

        $this->assertDatabaseHas('activity_logs', ['action' => 'test.event']);
    }

    public function test_prune_command_deletes_old_logs(): void
    {
        $old = ActivityLog::create(['action' => 'test.old', 'description' => 'old']);
        $old->created_at = now()->subDays(200);
        $old->save();
        ActivityLogger::log('test.fresh', null, [], 'fresh');

        $this->artisan('activity:prune', ['--days' => 180])
            ->assertSuccessful();

        $this->assertDatabaseMissing('activity_logs', ['action' => 'test.old']);
        $this->assertDatabaseHas('activity_logs', ['action' => 'test.fresh']);
    }
}
