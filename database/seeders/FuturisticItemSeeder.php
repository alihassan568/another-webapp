<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;

class FuturisticItemSeeder extends Seeder
{
    public function run(): void
    {
        $vendor = User::firstOrCreate(
            ['email' => 'vendor@another-go.com'],
            [
                'name' => 'Future Vendor',
                'role' => 'business',
                'password' => bcrypt('password123'),
                'email_verified_at' => now()
            ]
        );

        $categories = [
            'Electronics',
            'Fashion',
            'Food & Beverages',
            'Gadgets'
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(
                ['name' => $categoryName, 'user_id' => $vendor->id],
                ['name' => $categoryName, 'user_id' => $vendor->id]
            );
        }

        $futuristicItems = [
            [
                'name' => 'Holographic Gaming Console',
                'category' => 'Electronics',
                'sub_category' => 'Gaming',
                'description' => 'Next-gen holographic gaming experience with 360° immersive display and neural interface compatibility.',
                'price' => 1299,
                'image' => 'storage/images/items/holographic-console.jpg',
                'discount_percentage' => 15.0,
                'valid_from' => now()->timestamp,
                'valid_until' => now()->addDays(30)->timestamp,
                'status' => 'approved'
            ],
            [
                'name' => 'Smart Nano Fabric Jacket',
                'category' => 'Fashion',
                'sub_category' => 'Outerwear',
                'description' => 'Self-cleaning, temperature-regulating jacket with built-in air purification and UV protection.',
                'price' => 899,
                'image' => 'storage/images/items/nano-jacket.jpg',
                'discount_percentage' => 20.0,
                'valid_from' => now()->timestamp,
                'valid_until' => now()->addDays(15)->timestamp,
                'status' => 'approved'
            ],
            [
                'name' => 'Molecular Food Printer',
                'category' => 'Food & Beverages',
                'sub_category' => 'Kitchen Tech',
                'description' => 'Revolutionary 3D food printer that creates nutritious meals at molecular level. Supports 500+ recipes.',
                'price' => 2599,
                'image' => 'storage/images/items/food-printer.jpg',
                'discount_percentage' => 10.0,
                'valid_from' => now()->timestamp,
                'valid_until' => now()->addDays(45)->timestamp,
                'status' => 'approved'
            ],
            [
                'name' => 'Neural Interface Headband',
                'category' => 'Gadgets',
                'sub_category' => 'Wearables',
                'description' => 'Direct brain-computer interface for enhanced productivity, meditation, and virtual reality experiences.',
                'price' => 1899,
                'image' => 'storage/images/items/neural-headband.jpg',
                'discount_percentage' => 25.0,
                'valid_from' => now()->timestamp,
                'valid_until' => now()->addDays(20)->timestamp,
                'status' => 'approved'
            ],
            [
                'name' => 'Quantum Smartphone Pro',
                'category' => 'Electronics',
                'sub_category' => 'Mobile',
                'description' => 'Quantum-encrypted smartphone with holographic display, unlimited battery, and teleportation capabilities.',
                'price' => 3999,
                'image' => 'storage/images/items/quantum-phone.jpg',
                'status' => 'approved'
            ],
            [
                'name' => 'Levitating Sneakers',
                'category' => 'Fashion',
                'sub_category' => 'Footwear',
                'description' => 'Anti-gravity sneakers with magnetic levitation technology. Walk on air with style and comfort.',
                'price' => 1599,
                'image' => 'storage/images/items/levitating-sneakers.jpg',
                'discount_percentage' => 30.0,
                'valid_from' => now()->timestamp,
                'valid_until' => now()->addDays(10)->timestamp,
                'status' => 'approved'
            ]
        ];

        foreach ($futuristicItems as $itemData) {
            $itemData['user_id'] = $vendor->id;
            $itemData['quantity'] = rand(5, 50);
            $itemData['pickup_start_time'] = '09:00';
            $itemData['pickup_end_time'] = '18:00';
            $itemData['commission'] = 10.0;
            
            Item::create($itemData);
        }
    }
}