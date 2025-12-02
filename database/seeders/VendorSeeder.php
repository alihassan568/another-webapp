<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\BusinessProfile;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates test vendor users with complete business profiles and Stripe integration
     */
    public function run(): void
    {
        // Vendor 1: Pizza Restaurant (Stripe Onboarded)
        $vendor1 = User::create([
            'name' => 'Pizza Palace',
            'email' => 'vendor1@pizzapalace.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '+1-555-0101',
            'address' => '123 Main Street, Downtown',
            'role' => 'business',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'image' => 'storage/images/vender/pizza_palace.jpg',
            'stripe_account_id' => 'acct_test_pizza_' . uniqid(),
            'stripe_status' => 'complete',
            'stripe_onboarded_at' => now()->subDays(30),
            'charges_enabled' => true,
            'payouts_enabled' => true,
        ]);

        BusinessProfile::create([
            'user_id' => $vendor1->id,
            'business_type' => 'Restaurant,Fast Food',
            'owner_name' => 'John Smith',
            'opening_time' => '10:00 AM',
            'close_time' => '11:00 PM',
            'bank_title' => 'John Smith',
            'bank_name' => 'Chase Bank',
            'iban' => 'US89370400440532013000',
        ]);

        // Vendor 2: Grocery Store (Stripe Onboarded)
        $vendor2 = User::create([
            'name' => 'Fresh Mart Groceries',
            'email' => 'vendor2@freshmart.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '+1-555-0102',
            'address' => '456 Oak Avenue, Suburb',
            'role' => 'business',
            'latitude' => 40.7589,
            'longitude' => -73.9851,
            'image' => 'storage/images/vender/fresh_mart.jpg',
            // Stripe fields - fully onboarded
            'stripe_account_id' => 'acct_test_grocery_' . uniqid(),
            'stripe_status' => 'complete',
            'stripe_onboarded_at' => now()->subDays(45),
            'charges_enabled' => true,
            'payouts_enabled' => true,
        ]);

        BusinessProfile::create([
            'user_id' => $vendor2->id,
            'business_type' => 'Grocery',
            'owner_name' => 'Sarah Johnson',
            'opening_time' => '08:00 AM',
            'close_time' => '09:00 PM',
            'bank_title' => 'Sarah Johnson',
            'bank_name' => 'Bank of America',
            'iban' => 'US12345678901234567890',
        ]);

        // Vendor 3: Bakery (Stripe Onboarded)
        $vendor3 = User::create([
            'name' => 'Sweet Dreams Bakery',
            'email' => 'vendor3@sweetdreams.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '+1-555-0103',
            'address' => '789 Elm Street, Uptown',
            'role' => 'business',
            'latitude' => 40.7480,
            'longitude' => -73.9862,
            'image' => 'storage/images/vender/sweet_dreams.jpg',
            // Stripe fields
            'stripe_account_id' => 'acct_test_bakery_' . uniqid(),
            'stripe_status' => 'complete',
            'stripe_onboarded_at' => now()->subDays(60),
            'charges_enabled' => true,
            'payouts_enabled' => true,
        ]);

        BusinessProfile::create([
            'user_id' => $vendor3->id,
            'business_type' => 'Bakery',
            'owner_name' => 'Michael Brown',
            'opening_time' => '06:00 AM',
            'close_time' => '08:00 PM',
            'bank_title' => 'Michael Brown',
            'bank_name' => 'Wells Fargo',
            'iban' => 'US98765432109876543210',
        ]);

        // Vendor 4: Electronics Store (Stripe Pending)
        $vendor4 = User::create([
            'name' => 'Tech Hub Electronics',
            'email' => 'vendor4@techhub.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '+1-555-0104',
            'address' => '321 Pine Road, Business District',
            'role' => 'business',
            'latitude' => 40.7580,
            'longitude' => -73.9855,
            'image' => 'storage/images/vender/tech_hub.jpg',
            // Stripe fields - incomplete onboarding
            'stripe_account_id' => 'acct_test_electronics_' . uniqid(),
            'stripe_status' => 'incomplete',
            'stripe_onboarded_at' => null,
            'charges_enabled' => false,
            'payouts_enabled' => false,
        ]);

        BusinessProfile::create([
            'user_id' => $vendor4->id,
            'business_type' => 'Electronics',
            'owner_name' => 'David Lee',
            'opening_time' => '09:00 AM',
            'close_time' => '10:00 PM',
            'bank_title' => 'David Lee',
            'bank_name' => 'Citibank',
            'iban' => 'US11223344556677889900',
        ]);

        // Vendor 5: Pharmacy (Not Started Stripe)
        $vendor5 = User::create([
            'name' => 'HealthCare Pharmacy',
            'email' => 'vendor5@healthcare.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '+1-555-0105',
            'address' => '654 Maple Drive, Medical Center',
            'role' => 'business',
            'latitude' => 40.7614,
            'longitude' => -73.9776,
            'image' => 'storage/images/vender/healthcare.jpg',
            // Stripe fields - not started
            'stripe_account_id' => null,
            'stripe_status' => 'incomplete',
            'stripe_onboarded_at' => null,
            'charges_enabled' => false,
            'payouts_enabled' => false,
        ]);

        BusinessProfile::create([
            'user_id' => $vendor5->id,
            'business_type' => 'Pharmacy',
            'owner_name' => 'Emily Davis',
            'opening_time' => '08:00 AM',
            'close_time' => '10:00 PM',
            'bank_title' => 'Emily Davis',
            'bank_name' => 'TD Bank',
            'iban' => 'US99887766554433221100',
        ]);

        $this->command->info('✅ Created 5 vendor users with business profiles and Stripe integration');
        $this->command->info('   - 3 vendors fully onboarded with Stripe');
        $this->command->info('   - 1 vendor with incomplete Stripe onboarding');
        $this->command->info('   - 1 vendor without Stripe account');
        $this->command->info('');
        $this->command->info('📧 All vendors use password: password123');
        $this->command->info('');
        $this->command->info('Vendor Emails:');
        $this->command->info('  - vendor1@pizzapalace.com (Pizza Palace - Stripe ✓)');
        $this->command->info('  - vendor2@freshmart.com (Fresh Mart - Stripe ✓)');
        $this->command->info('  - vendor3@sweetdreams.com (Sweet Dreams - Stripe ✓)');
        $this->command->info('  - vendor4@techhub.com (Tech Hub - Stripe Incomplete)');
        $this->command->info('  - vendor5@healthcare.com (HealthCare - No Stripe)');
    }
}
