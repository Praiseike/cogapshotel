<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Standard' => [
                'slug' => 'standard',
                'description' => 'Comfortable, thoughtfully designed rooms for the practical traveller.',
                'price_from' => 45000,
                'image' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&q=80',
            ],
            'Deluxe' => [
                'slug' => 'deluxe',
                'description' => 'Spacious rooms with premium furnishings and city views.',
                'price_from' => 80000,
                'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80',
            ],
            'Executive' => [
                'slug' => 'executive',
                'description' => 'Designed for business travellers who expect more.',
                'price_from' => 120000,
                'image' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&q=80',
            ],
            'Suite' => [
                'slug' => 'suite',
                'description' => 'Generous living spaces and panoramic skyline views.',
                'price_from' => 180000,
                'image' => 'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?w=800&q=80',
            ],
            'Presidential' => [
                'slug' => 'presidential',
                'description' => 'Our most prestigious accommodation, reserved for those who demand the best.',
                'price_from' => 350000,
                'image' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&q=80',
            ],
        ];

        $categoryModels = [];
        foreach ($categories as $name => $data) {
            $categoryModels[$name] = Category::create([
                'name' => $name,
                'slug' => $data['slug'],
                'description' => $data['description'],
                'image' => $data['image'],
                'sort_order' => array_search($name, array_keys($categories)) + 1,
                'is_active' => true,
            ]);
        }

        $rooms = [
            ['Standard', 'Cozy Double', 45000, 2, 'Queen bed', ['Free Wi-Fi', 'Air conditioning', 'Smart TV', 'Mini fridge', 'Daily housekeeping']],
            ['Standard', 'Twin Standard', 50000, 2, 'Two single beds', ['Free Wi-Fi', 'Air conditioning', 'Smart TV', 'Work desk', 'Daily housekeeping']],
            ['Deluxe', 'Deluxe King', 80000, 2, 'King bed', ['Free Wi-Fi', 'Mini bar', 'Rain shower', 'City view', 'Nespresso machine']],
            ['Deluxe', 'Deluxe Twin', 85000, 3, 'Two queen beds', ['Free Wi-Fi', 'Mini bar', 'Rain shower', 'City view', 'Nespresso machine']],
            ['Executive', 'Executive Lounge Access', 120000, 2, 'King bed', ['Free Wi-Fi', 'Lounge access', 'Late checkout', 'Bathtub', 'Workspace']],
            ['Executive', 'Executive Corner', 135000, 3, 'King bed', ['Free Wi-Fi', 'Lounge access', 'Late checkout', 'Corner windows', 'Workspace']],
            ['Suite', 'Skyline Suite', 180000, 3, 'King bed', ['Living room', 'Dining area', 'Private balcony', 'Kitchenette', 'Butler service']],
            ['Suite', 'Family Suite', 220000, 5, 'King + bunk', ['Two bedrooms', 'Living room', 'Kitchenette', 'Kids amenities', 'Balcony']],
            ['Presidential', 'Presidential Penthouse', 350000, 4, 'King bed', ['Private terrace', 'Dining room', 'Jacuzzi', 'Chef service', 'Chauffeur']],
        ];

        $roomImages = [
            'Cozy Double' => ['https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80'],
            'Twin Standard' => ['https://images.unsplash.com/photo-1591088398332-8a7791972843?w=1200&q=80', 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80'],
            'Deluxe King' => ['https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80', 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80'],
            'Deluxe Twin' => ['https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80', 'https://images.unsplash.com/photo-1591088398332-8a7791972843?w=1200&q=80'],
            'Executive Lounge Access' => ['https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80', 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80'],
            'Executive Corner' => ['https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80'],
            'Skyline Suite' => ['https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?w=1200&q=80', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80'],
            'Family Suite' => ['https://images.unsplash.com/photo-1584132967334-10e028bd69f7?w=1200&q=80', 'https://images.unsplash.com/photo-1591088398332-8a7791972843?w=1200&q=80'],
            'Presidential Penthouse' => ['https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80', 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80'],
        ];

        foreach ($rooms as [$categoryName, $name, $price, $capacity, $bedType, $amenities]) {
            Room::create([
                'category_id' => $categoryModels[$categoryName]->id,
                'name' => $name,
                'price_per_night' => $price,
                'capacity' => $capacity,
                'bed_type' => $bedType,
                'amenities' => $amenities,
                'images' => $roomImages[$name],
                'is_available' => true,
                'status' => 'available',
                'description' => "Our {$name} offers a serene retreat with premium bedding, thoughtful amenities, and the attentive service our guests have come to expect. Perfect for travellers seeking comfort and convenience.",
            ]);
        }

        $services = [
            ['name' => 'Spa & Wellness', 'category' => 'Wellness', 'price' => 25000, 'image' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=800&q=80', 'description' => 'Relax with massages, facials, and holistic treatments in our tranquil spa sanctuary.'],
            ['name' => 'Outdoor Pool', 'category' => 'Leisure', 'price' => 0, 'image' => 'https://images.unsplash.com/photo-1576017551620-1e2b1d4a4b1f?w=800&q=80', 'description' => 'Take a refreshing dip in our temperature-controlled outdoor pool, surrounded by lush gardens.'],
            ['name' => 'Fine Dining', 'category' => 'Restaurant', 'price' => 35000, 'image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80', 'description' => 'Award-winning chefs craft seasonal menus with local and international flavours.'],
            ['name' => 'Fitness Center', 'category' => 'Wellness', 'price' => 0, 'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&q=80', 'description' => 'State-of-the-art gym equipment and complimentary fitness classes for our guests.'],
        ];

        foreach ($services as $service) {
            Service::create(array_merge($service, ['is_active' => true]));
        }

        $galleryItems = [
            ['title' => 'Hotel Lobby', 'image_path' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80', 'caption' => 'Our grand lobby with 24/7 concierge', 'sort_order' => 1],
            ['title' => 'Swimming Pool', 'image_path' => 'https://images.unsplash.com/photo-1576017551620-1e2b1d4a4b1f?w=1200&q=80', 'caption' => 'Outdoor infinity pool', 'sort_order' => 2],
            ['title' => 'Fine Dining Room', 'image_path' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1200&q=80', 'caption' => 'Seasonal fine dining experience', 'sort_order' => 3],
            ['title' => 'Presidential Suite', 'image_path' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80', 'caption' => 'The pinnacle of luxury', 'sort_order' => 4],
            ['title' => 'Conference Hall', 'image_path' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=1200&q=80', 'caption' => 'Modern conference facilities', 'sort_order' => 5],
            ['title' => 'Garden Terrace', 'image_path' => 'https://images.unsplash.com/photo-1529290130-4ca3753253ae?w=1200&q=80', 'caption' => 'Quiet corners to unwind', 'sort_order' => 6],
        ];

        foreach ($galleryItems as $item) {
            Gallery::create(array_merge($item, ['is_active' => true]));
        }
    }
}
