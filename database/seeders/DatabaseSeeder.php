<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Admin User
        User::create([
            'name' => 'OceanBreeze Admin',
            'email' => 'admin@oceanbreeze.lk',
            'password' => Hash::make('admin123'),
            'contact_number' => '0771234567',
            'role' => 'admin',
        ]);

        // 2. Create a Sample Guest User (handy for testing bookings)
        User::create([
            'name' => 'Test Guest',
            'email' => 'guest@oceanbreeze.lk',
            'password' => Hash::make('guest123'),
            'contact_number' => '0719876543',
            'role' => 'guest',
        ]);

        // 3. Seed Coastal Villa Rooms
        $rooms = [
            [
                'room_name' => 'Coral Reef Beachfront Villa',
                'room_type' => 'Luxury Villa',
                'description' => 'Private beachfront villa with direct ocean access, infinity plunge pool, king-size bed, and panoramic sunset deck.',
                'price_per_night' => 45000.00,
                'max_guests' => 4,
                'room_status' => true,
            ],
            [
                'room_name' => 'Palm Breeze Deluxe Suite',
                'room_type' => 'Deluxe Suite',
                'description' => 'Spacious upper-floor suite overlooking the tropical gardens and Indian Ocean, featuring a private balcony and rain shower.',
                'price_per_night' => 28000.00,
                'max_guests' => 2,
                'room_status' => true,
            ],
            [
                'room_name' => 'Lagoon View Family Residence',
                'room_type' => 'Family Suite',
                'description' => 'Two-bedroom coastal residence ideal for families, complete with a private lounge area, kitchenette, and lagoon terrace.',
                'price_per_night' => 36500.00,
                'max_guests' => 5,
                'room_status' => true,
            ],
            [
                'room_name' => 'Sunset Horizon Studio',
                'room_type' => 'Standard Double',
                'description' => 'Cozy coastal studio steps away from the shoreline, equipped with air conditioning, high-speed Wi-Fi, and ocean breeze patio.',
                'price_per_night' => 18500.00,
                'max_guests' => 2,
                'room_status' => true,
            ],
            [
                'room_name' => 'Oceanfront Honeymoon Sanctuary',
                'room_type' => 'Luxury Villa',
                'description' => 'Secluded cliffside sanctuary with a private outdoor jacuzzi, four-poster canopy bed, candlelit terrace, and uninterrupted Indian Ocean sunset views.',
                'price_per_night' => 52000,
                'max_guests' => 2,
                'room_status' => true,
            ],
            [
                'room_name' => 'Coconut Grove Garden Cabana',
                'room_type' => 'Deluxe Suite',
                'description' => 'Tropical timber cabana nestled among swaying coconut palms steps from the beach, featuring a private hammock veranda and open-air stone bath.',
                'price_per_night' => 31500,
                'max_guests' => 3,
                'room_status' => true,
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}