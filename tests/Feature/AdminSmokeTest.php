<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use Tests\TestCase as BaseTestCase;

final class AdminSmokeTest extends BaseTestCase
{
    protected function admin(): User
    {
        $admin = User::where('role', 'admin')->first();

        $this->assertNotNull($admin, 'Admin user not found. Run: php artisan db:seed');

        return $admin;
    }

    protected function guest(): User
    {
        $guest = User::where('role', 'guest')->first();

        $this->assertNotNull($guest, 'Guest user not found. Run: php artisan db:seed');

        return $guest;
    }

    public function test_signed_in_users_bounce_off_guest_pages(): void
    {
        $this->actingAs($this->guest())
            ->get(route('login'))
            ->assertRedirect(route('dashboard.index'));

        $this->actingAs($this->admin())
            ->get(route('login'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_public_pages_render(): void
    {
        $room = Room::first();
        $this->assertNotNull($room, 'No rooms seeded.');

        $routes = [
            route('home'),
            route('rooms.index'),
            route('rooms.show', $room->slug),
            route('services.index'),
            route('gallery.index'),
            route('contact.show'),
            route('about'),
            route('terms'),
            route('privacy'),
            route('login'),
            route('register'),
        ];

        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertOk();

            $path = parse_url($url, PHP_URL_PATH);
            $response->assertSee('<html', false);
        }
    }

    public function test_unauthenticated_admin_access_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_non_admin_user_is_forbidden_from_admin(): void
    {
        $this->actingAs($this->guest())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_dashboard_and_list_pages_render(): void
    {
        $this->actingAs($this->admin());

        $routes = [
            'admin.dashboard',

            'admin.categories.index',
            'admin.categories.create',

            'admin.rooms.index',
            'admin.rooms.create',

            'admin.bookings.index',

            'admin.services.index',
            'admin.services.create',

            'admin.gallery.index',
            'admin.gallery.create',

            'admin.contacts.index',

            'admin.settings.index',
        ];

        foreach ($routes as $name) {
            $this->get(route($name))->assertOk();
        }
    }

    public function test_admin_edit_pages_render_for_existing_records(): void
    {
        $this->actingAs($this->admin());

        $category = Category::first();
        $room = Room::first();
        $service = Service::first();
        $gallery = Gallery::first();
        $contact = Contact::first();

        if ($category) {
            $this->get(route('admin.categories.edit', $category))->assertOk();
        }

        if ($room) {
            $this->get(route('admin.rooms.edit', $room))->assertOk();
        }

        if ($service) {
            $this->get(route('admin.services.edit', $service))->assertOk();
        }

        if ($gallery) {
            $this->get(route('admin.gallery.edit', $gallery))->assertOk();
        }

        if ($contact) {
            $this->get(route('admin.contacts.show', $contact))->assertOk();
        }

        $booking = Booking::first();
        if ($booking) {
            $this->get(route('admin.bookings.show', $booking))->assertOk();
        }
    }
}