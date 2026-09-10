<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'hotel_name' => 'Luxury Grand Hotel',
            'hotel_email' => 'reservations@luxurygrand.com',
            'hotel_phone' => '+234 800 555 0134',
            'hotel_address' => '12 Independence Avenue, Victoria Island, Lagos',
            'hotel_description' => 'Experience unparalleled luxury and comfort. Our hotel offers world-class amenities, exceptional service, and an unforgettable stay.',
        ];

        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value, 'text');
        }
    }
}
