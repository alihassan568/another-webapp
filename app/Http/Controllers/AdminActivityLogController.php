<?php

namespace App\Http\Controllers;

use App\Models\AdminActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminActivityLogController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', AdminActivityLog::class);

        $adminId = $request->query('admin_id');
        $action = $request->query('action');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        // Build query
        $logs = AdminActivityLog::with(['adminUser', 'invitedBy'])
            ->when($adminId, function ($query) use ($adminId) {
                return $query->where('admin_user_id', $adminId);
            })
            ->when($action, function ($query) use ($action) {
                return $query->where('action', $action);
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->latest()
            ->paginate(20);

        // Get all admins for filter dropdown (only users with CAN_LIST_ITEMS permission)
        $admins = User::where('role', '!=', 'vendor')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get()
            ->filter(function ($user) {
                return $user->can(\App\Modules\User\Enums\Permissions::CAN_LIST_ITEMS->value);
            });

        // Available actions for filter
        $actions = [
            'approve_item' => 'Approve Item',
            'reject_item' => 'Reject Item',
            'set_commission' => 'Set Commission',
            'invite_user' => 'Invite User',
            'create_role' => 'Create Role',
            'update_role' => 'Update Role',
            'block_vendor' => 'Block Vendor',
            'unblock_vendor' => 'Unblock Vendor',
            'list_vendors' => 'List Vendors',
            'view_vendor' => 'View Vendor',
        ];

        return view('admin.activity-logs.index', compact('logs', 'admins', 'actions'));
    }

    /**
     * Display activities for a specific admin
     */
    public function byAdmin($adminId)
    {
        Gate::authorize('viewAny', AdminActivityLog::class);

        $admin = User::findOrFail($adminId);
        
        $logs = AdminActivityLog::with(['adminUser', 'invitedBy'])
            ->where('admin_user_id', $adminId)
            ->latest()
            ->paginate(20);

        return view('admin.activity-logs.by-admin', compact('logs', 'admin'));
    }

    /**
     * Display activities of users invited by specific admin
     */
    public function byInviter($inviterId)
    {
        Gate::authorize('viewAny', AdminActivityLog::class);

        $inviter = User::findOrFail($inviterId);
        
        // Get users invited by this admin
        $invitedUsers = User::where('invited_by_user_id', $inviterId)
            ->pluck('id')
            ->toArray();

        $logs = AdminActivityLog::with(['adminUser', 'invitedBy'])
            ->whereIn('admin_user_id', $invitedUsers)
            ->latest()
            ->paginate(20);

        return view('admin.activity-logs.by-inviter', compact('logs', 'inviter', 'invitedUsers'));
    }

    /**
     * Get activity statistics
     */
    public function statistics()
    {
        Gate::authorize('viewAny', AdminActivityLog::class);

        $stats = [
            'total_activities' => AdminActivityLog::count(),
            'today_activities' => AdminActivityLog::whereDate('created_at', today())->count(),
            'this_week_activities' => AdminActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'by_action' => AdminActivityLog::selectRaw('action, count(*) as count')
                ->groupBy('action')
                ->pluck('count', 'action')
                ->toArray(),
            'top_active_admins' => AdminActivityLog::with('adminUser')
                ->selectRaw('admin_user_id, count(*) as activity_count')
                ->groupBy('admin_user_id')
                ->orderByDesc('activity_count')
                ->limit(10)
                ->get(),
        ];

        return view('admin.activity-logs.statistics', compact('stats'));
    }
}
