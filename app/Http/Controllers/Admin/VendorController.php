<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of vendors.
     */
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasPermissionTo('Can list vendors'), 403);

        $query = User::where('role', 'business')
            ->latest();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->whereNull('blocked_at');
            } elseif ($request->status === 'blocked') {
                $query->whereNotNull('blocked_at');
            }
        }

        $vendors = $query->paginate(15)->withQueryString();

        return view('admin.vendors.index', [
            'vendors' => $vendors,
            'search' => $request->search ?? '',
            'status' => $request->status ?? 'all',
        ]);
    }

    /**
     * Display the specified vendor.
     */
    public function show(User $vendor)
    {
        // Ensure the user is actually a vendor
        if ($vendor->role !== 'business') {
            abort(404, 'Vendor not found');
        }

        abort_unless(auth()->user()->hasPermissionTo('Can view vendor'), 403);

        // Load vendor's items
        $vendor->load(['items' => function ($query) {
            $query->latest()->limit(10);
        }]);

        $totalItems = $vendor->items()->count();
        $approvedItems = $vendor->items()->where('status', 'approved')->count();
        $pendingItems = $vendor->items()->where('status', 'pending')->count();
        $rejectedItems = $vendor->items()->where('status', 'rejected')->count();

        return view('admin.vendors.show', [
            'vendor' => $vendor,
            'totalItems' => $totalItems,
            'approvedItems' => $approvedItems,
            'pendingItems' => $pendingItems,
            'rejectedItems' => $rejectedItems,
        ]);
    }

    /**
     * Block the specified vendor.
     */
    public function block(User $vendor)
    {
        // Ensure the user is actually a vendor
        if ($vendor->role !== 'business') {
            abort(404, 'Vendor not found');
        }

        abort_unless(auth()->user()->hasPermissionTo('Can block vendor'), 403);

        // Prevent blocking yourself
        if (auth()->id() === $vendor->id) {
            return redirect()->back()->with('error', 'You cannot block yourself.');
        }

        if ($vendor->blocked_at) {
            return redirect()->back()->with('error', 'Vendor is already blocked.');
        }

        $vendor->update([
            'blocked_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Vendor has been blocked successfully.');
    }

    /**
     * Unblock the specified vendor.
     */
    public function unblock(User $vendor)
    {
        // Ensure the user is actually a vendor
        if ($vendor->role !== 'business') {
            abort(404, 'Vendor not found');
        }

        abort_unless(auth()->user()->hasPermissionTo('Can unblock vendor'), 403);

        if (!$vendor->blocked_at) {
            return redirect()->back()->with('error', 'Vendor is not blocked.');
        }

        $vendor->update([
            'blocked_at' => null,
        ]);

        return redirect()->back()->with('success', 'Vendor has been unblocked successfully.');
    }
}
