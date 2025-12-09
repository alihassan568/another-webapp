<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\User::class => \App\Policies\VendorPolicy::class,
        \App\Models\AdminActivityLog::class => \App\Policies\AdminActivityLogPolicy::class,
        \App\Models\Role::class => \App\Policies\RolePolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
