<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Check if admin already exists
        $existingAdmin = User::where('email', 'admin@foodapp.com')->first();
        
        if ($existingAdmin) {
            $this->command->info('❌ Admin user already exists!');
            return;
        }

        // Create admin user
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@foodapp.com',
            'password' => Hash::make('admin123'),
            'phone' => '+1-555-ADMIN',
            'address' => 'Food App HQ, Admin Office',
            'role' => 'admin',
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
            'image' => null,
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Admin user created successfully!');
        $this->command->newLine();
        $this->command->info('📧 Email: admin@foodapp.com');
        $this->command->info('🔑 Password: admin123');
        $this->command->info('👤 Role: admin');
    }
}
