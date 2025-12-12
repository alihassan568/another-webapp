<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\User\Enums\Permissions;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use \App\Traits\TracksAdminActivity;
    public function index(Request $request)
    {
        $this->logActivity(
            action: 'list_users',
            description: 'Viewed user list',
            actionType: 'viewed',
            targetType: 'user',
            metadata: ['search' => $request->search ?? '', 'status' => $request->status ?? 'all', 'role' => $request->role ?? 'all']
        );
        
        // You might want to update this permission check to something more generic or specific to users
        // abort_unless(auth()->user()->hasPermissionTo(Permissions::CAN_LIST_USERS), 403); 
        
        $query = User::latest();
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        } else {
            // By default showing both, or maybe exclude admin?
            // $query->whereIn('role', ['user', 'business']);
            $query->where('role', '!=', 'admin'); // Assuming we don't want to manage admins here
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->whereNull('blocked_at');
            } elseif ($request->status === 'blocked') {
                $query->whereNotNull('blocked_at');
            }
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $request->search ?? '',
            'status' => $request->status ?? 'all',
            'role' => $request->role ?? 'all',
        ]);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Log user view activity
        $this->logActivity(
            action: 'view_user',
            description: "Viewed user: {$user->name}",
            actionType: 'viewed',
            targetId: $user->id,
            targetType: 'user',
            metadata: ['user_name' => $user->name, 'user_email' => $user->email]
        );
        
        // abort_unless(auth()->user()->hasPermissionTo(Permissions::CAN_VIEW_USER), 403);

        if ($user->role === 'business') {
             // Load vendor's items
            $user->load(['items' => function ($query) {
                $query->latest()->limit(10);
            }]);
            
            $totalItems = $user->items()->count();
            $approvedItems = $user->items()->where('status', 'approved')->count();
            $pendingItems = $user->items()->where('status', 'pending')->count();
            $rejectedItems = $user->items()->where('status', 'rejected')->count();

            return view('admin.users.show', [
                'user' => $user,
                'totalItems' => $totalItems,
                'approvedItems' => $approvedItems,
                'pendingItems' => $pendingItems,
                'rejectedItems' => $rejectedItems,
            ]);
        }
       
        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Block the specified user.
     */
    public function block(User $user)
    {
        // abort_unless(auth()->user()->hasPermissionTo(Permissions::CAN_BLOCK_USER), 403);

        // Prevent blocking yourself
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot block yourself.');
        }

        if ($user->blocked_at) {
            return redirect()->back()->with('error', 'User is already blocked.');
        }

        $user->update([
            'blocked_at' => now(),
        ]);

        // Log block activity
        $this->logActivity(
            action: 'block_user',
            description: "Blocked user: {$user->name}",
            actionType: 'blocked',
            targetId: $user->id,
            targetType: 'user',
            metadata: ['user_name' => $user->name, 'user_email' => $user->email]
        );

        return redirect()->back()->with('success', 'User has been blocked successfully.');
    }

    /**
     * Unblock the specified user.
     */
    public function unblock(User $user)
    {
        // abort_unless(auth()->user()->hasPermissionTo(Permissions::CAN_UNBLOCK_USER), 403);

        if (!$user->blocked_at) {
            return redirect()->back()->with('error', 'User is not blocked.');
        }

        $user->update([
            'blocked_at' => null,
        ]);

        // Log unblock activity
        $this->logActivity(
            action: 'unblock_user',
            description: "Unblocked user: {$user->name}",
            actionType: 'unblocked',
            targetId: $user->id,
            targetType: 'user',
            metadata: ['user_name' => $user->name, 'user_email' => $user->email]
        );

        return redirect()->back()->with('success', 'User has been unblocked successfully.');
    }
}
