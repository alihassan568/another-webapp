<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        Gate::authorize('viewDashboard', User::class);

        $totalVenders = User::where('role', '=', 'business')->count();
        $totalUsers = User::where('role', '=', 'user')->count();
        $pendingItems = Item::where('status', '=', 'pending')->count();
        $acceptedItems = Item::where('status', '=', 'accepted')->count();

        return view(
            'dashboard',
            compact('totalVenders', 'totalUsers', 'pendingItems', 'acceptedItems')
        );
    }
}
