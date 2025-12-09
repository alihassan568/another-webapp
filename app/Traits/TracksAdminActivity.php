<?php

namespace App\Traits;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

trait TracksAdminActivity
{
    /**
     * Log an admin activity
     */
    protected function logActivity(
        string $action,
        string $description,
        ?string $actionType = null,
        ?int $targetId = null,
        ?string $targetType = null,
        ?array $metadata = null
    ): void {
        $admin = Auth::user();
        
        if (!$admin) {
            return;
        }

        // Get the user who invited this admin (if any)
        $invitedBy = $admin->invited_by_user_id ?? null;

        $log = AdminActivityLog::create([
            'admin_user_id' => $admin->id,
            'invited_by_user_id' => $invitedBy,
            'action' => $action,
            'action_type' => $actionType,
            'description' => $description,
            'target_id' => $targetId,
            'target_type' => $targetType,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        \Log::info('Admin activity log created', [
            'id' => $log->id ?? null,
            'admin_user_id' => $admin->id,
            'action' => $action,
            'description' => $description,
            'target_id' => $targetId,
            'target_type' => $targetType,
        ]);
    }

    /**
     * Log item approval
     */
    protected function logItemApproval(int $itemId, string $itemName, int $vendorId): void
    {
        $this->logActivity(
            action: 'approve_item',
            description: "Approved item: {$itemName}",
            actionType: 'approved',
            targetId: $itemId,
            targetType: 'item',
            metadata: ['vendor_id' => $vendorId, 'item_name' => $itemName]
        );
    }

    /**
     * Log item rejection
     */
    protected function logItemRejection(int $itemId, string $itemName, int $vendorId, ?string $reason = null): void
    {
        $this->logActivity(
            action: 'reject_item',
            description: "Rejected item: {$itemName}",
            actionType: 'rejected',
            targetId: $itemId,
            targetType: 'item',
            metadata: [
                'vendor_id' => $vendorId,
                'item_name' => $itemName,
                'rejection_reason' => $reason
            ]
        );
    }

    /**
     * Log commission setting
     */
    protected function logCommissionSet(int $itemId, string $itemName, float $commission, int $vendorId): void
    {
        $this->logActivity(
            action: 'set_commission',
            description: "Set commission to {$commission}% for item: {$itemName}",
            actionType: 'set',
            targetId: $itemId,
            targetType: 'item',
            metadata: [
                'item_name' => $itemName,
                'commission' => $commission,
                'vendor_id' => $vendorId
            ]
        );
    }

    /**
     * Log user invitation
     */
    protected function logUserInvite(int $inviteId, string $email, string $roleName): void
    {
        $this->logActivity(
            action: 'invite_user',
            description: "Invited {$email} as {$roleName}",
            actionType: 'sent',
            targetId: $inviteId,
            targetType: 'invite',
            metadata: ['email' => $email, 'role' => $roleName]
        );
    }

    /**
     * Log role creation
     */
    protected function logRoleCreation(int $roleId, string $roleName, array $permissions): void
    {
        $this->logActivity(
            action: 'create_role',
            description: "Created role: {$roleName}",
            actionType: 'created',
            targetId: $roleId,
            targetType: 'role',
            metadata: ['role_name' => $roleName, 'permissions_count' => count($permissions)]
        );
    }

    /**
     * Log role update
     */
    protected function logRoleUpdate(int $roleId, string $roleName, array $permissions): void
    {
        $this->logActivity(
            action: 'update_role',
            description: "Updated role: {$roleName}",
            actionType: 'updated',
            targetId: $roleId,
            targetType: 'role',
            metadata: ['role_name' => $roleName, 'permissions_count' => count($permissions)]
        );
    }
}
