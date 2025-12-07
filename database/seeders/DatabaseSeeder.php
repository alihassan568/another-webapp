<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Modules\User\Enums\Roles;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('app:update-roles-and-permissions');

        $user = User::create([
            'name' => 'Haider Lasani',
            'email' => 'haider@eliyce.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $user1 = User::create([
            'name' => 'Ali Hassan',
            'email' => 'ali@eliyce.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'name' => 'Muhammad Saqlain',
            'email' => 'saqlain@eliyce.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $admin = Role::where('name', Roles::SUPER_ADMIN)->first();

        $user->assignRole($admin);
        $user1->assignRole($admin);
        $user2->assignRole($admin);
    }
}
