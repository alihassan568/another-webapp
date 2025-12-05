<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $latestItems = Item::with('user')
            ->where('status', 'approved')
            ->whereNotNull('image')
            ->latest()
            ->limit(4)
            ->get();

        $totalApprovedItems = Item::where('status', 'approved')->count();

        $totalCategories = Category::count();

        $totalVendors = \App\Models\User::where('role', 'business')->count();

        return view('welcome', compact(
            'latestItems', 
            'totalApprovedItems', 
            'totalCategories', 
            'totalVendors'
        ));
    }
}