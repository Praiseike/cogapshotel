<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class ReleaseExpiredBookings extends Command
{
    protected $signature = 'bookings:release-expired';

    protected $description = 'Release pending bookings that have not been paid within 30 minutes';

    public function handle(BookingService $bookingService): int
    {
        $count = $bookingService->releaseExpiredBookings();

        if ($count > 0) {
            $this->info("Released {$count} expired pending bookings.");
        } else {
            $this->line('No expired bookings to release.');
        }

        return Command::SUCCESS;
    }
}
