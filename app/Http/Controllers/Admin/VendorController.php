<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\User\Enums\Permissions;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    use \App\Traits\TracksAdminActivity;
    public function index(Request $request)
    {
        $this->logActivity(
            action: 'list_vendors',
            description: 'Viewed vendor list',
            actionType: 'viewed',
            targetType: 'vendor',
            metadata: ['search' => $request->search ?? '', 'status' => $request->status ?? 'all']
        );
        abort_unless(auth()->user()->hasPermissionTo(Permissions::CAN_LIST_VENDORS), 403);
        $query = User::where('role', 'business')
            ->latest();
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
        // Log vendor view activity
        $this->logActivity(
            action: 'view_vendor',
            description: "Viewed vendor: {$vendor->name}",
            actionType: 'viewed',
            targetId: $vendor->id,
            targetType: 'vendor',
            metadata: ['vendor_name' => $vendor->name, 'vendor_email' => $vendor->email]
        );
        // Ensure the user is actually a vendor
        if ($vendor->role !== 'business') {
            abort(404, 'Vendor not found');
        }

        abort_unless(auth()->user()->hasPermissionTo(Permissions::CAN_VIEW_VENDOR), 403);

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

        abort_unless(auth()->user()->hasPermissionTo(Permissions::CAN_BLOCK_VENDOR), 403);

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

        // Log vendor block activity
        $this->logActivity(
            action: 'block_vendor',
            description: "Blocked vendor: {$vendor->name}",
            actionType: 'blocked',
            targetId: $vendor->id,
            targetType: 'vendor',
            metadata: ['vendor_name' => $vendor->name, 'vendor_email' => $vendor->email]
        );

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

        abort_unless(auth()->user()->hasPermissionTo(Permissions::CAN_UNBLOCK_VENDOR), 403);

        if (!$vendor->blocked_at) {
            return redirect()->back()->with('error', 'Vendor is not blocked.');
        }

        $vendor->update([
            'blocked_at' => null,
        ]);

        // Log vendor unblock activity
        $this->logActivity(
            action: 'unblock_vendor',
            description: "Unblocked vendor: {$vendor->name}",
            actionType: 'unblocked',
            targetId: $vendor->id,
            targetType: 'vendor',
            metadata: ['vendor_name' => $vendor->name, 'vendor_email' => $vendor->email]
        );

        return redirect()->back()->with('success', 'Vendor has been unblocked successfully.');
    }
}
