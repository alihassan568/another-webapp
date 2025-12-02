<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CreateSuperUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:superadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Asking for user input
        $name = $this->ask('Enter your name:');
        $email = $this->ask('Enter your email:');
        $password = $this->secret('Enter your password (input hidden):');
        
        // Confirm details before proceeding
        if ($this->confirm("Do you wish to create this user?")) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => $password,
                    'role' => 'admin',
                    'email_verified_at' => now()
                ]
            );

            $this->info("Super Admin '{$user->name}' created successfully!");
        } else {
            $this->warn("Operation cancelled.");
        }
    }
}
