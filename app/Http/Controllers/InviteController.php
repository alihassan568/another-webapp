<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteRequest;
use App\Http\Resources\InviteResource;
use App\Mail\InviteUserMail;
use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use App\Traits\TracksAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class InviteController extends Controller
{
    use TracksAdminActivity;
    public function index()
    {
        Gate::authorize('viewAny', Invite::class);
        $invites = Invite::with('role')->latest()->paginate(15);
        // Exclude Super Admin role from invitable roles
        $roles = Role::where('name', '!=', 'Super Admin')->select('id', 'name')->get();
        
        return view('admin.invites.index', [
            'invites' => $invites,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Invite::class);
        // Exclude Super Admin role from invitable roles
        $roles = Role::where('name', '!=', 'Super Admin')->select('id', 'name')->get();
        return view('admin.invites.create', [
            'roles' => $roles,
        ]);
    }

    public function store(InviteRequest $request)
    {
        Gate::authorize('create', Invite::class);
        $token = Str::random(32);
        $inviteData = [
            'email' => $request->email,
            'role_id' => $request->role_id,
            'token' => $token,
            'status' => 'pending',
            'created_by_user_id' => auth()->id(), // Track who created this invite
        ];

        $role = Role::find($request->role_id);
        $type = strtolower($role->name);

        if (in_array($type, ['admin', 'editor', 'manager', 'worker'])) {
            $subject = 'Invitation to join as ' . ucfirst($type);
            $message = "You are invited to join our platform as a $type to help manage and grow our website. Please click the link below to accept your invitation.";
        } else {
            $subject = 'Invitation to join our platform';
            $message = "You are invited to join our platform to buy packages and improve your study skills. Please click the link below to accept your invitation.";
        }

        $invite = Invite::create($inviteData);
        
        // Log the invitation activity
        $this->logUserInvite($invite->id, $request->email, $role->name);
        
        // Generate invite link
        $inviteLink = route('invite.accept', $invite->token);
        
        // Send invitation email
        try {
            Mail::to($request->email)->send(new InviteUserMail(
                $inviteLink,
                $request->email,
                $role->name,
                $subject,
                $message // now passed as inviteMessage
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to send invitation email: ' . $e->getMessage());
            return redirect()->route('invite.index')->with('error', 'Invitation created but email failed to send.');
        }

        return redirect()->route('invite.index')->with('success', 'Invitation sent successfully!');
    }

    public function accept($token)
    {
        $invite = Invite::where('token', $token)->where('status', 'pending')->firstOrFail();
        return view('admin.invites.accept', [
            'invite' => $invite,
        ]);
    }

    public function confirmAccept(Request $request, $token)
    {
        $invite = Invite::where('token', $token)->where('status', 'pending')->firstOrFail();
        
        // Check if email already exists
        $existingUser = User::where('email', $invite->email)->first();
        if ($existingUser) {
            return redirect()->route('invite.accept', $token)
                ->with('error', 'User with this email already exists.');
        }

        // Generate random password
        $password = Str::random(12);
        
        // Determine role type for legacy column (default to 'user' if not admin/vendor)
        $roleName = strtolower($invite->role->name);
        $legacyRole = in_array($roleName, ['admin', 'vendor']) ? $roleName : 'user';
        
        // Get the inviter from the Invite model (we need to add this field)
        // For now, we'll track who created the invite via a new column
        $inviterId = $invite->created_by_user_id ?? null;
        
        // Create user
        $user = User::create([
            'name' => explode('@', $invite->email)[0], // Use email prefix as name
            'email' => $invite->email,
            'password' => bcrypt($password),
            'email_verified_at' => now(),
            'role' => $legacyRole, // For legacy role column
            'invited_by_user_id' => $inviterId, // Track who invited this user
        ]);

        // Assign Spatie role
        $user->assignRole($invite->role);

        // Update invite status
        $invite->update(['status' => 'accepted']);

        // Return to success page with credentials
        return view('admin.invites.success', [
            'email' => $invite->email,
            'password' => $password,
            'role' => $invite->role->name,
        ]);
    }

    public function cancel($token)
    {
        $invite = Invite::where('token', $token)->where('status', 'pending')->firstOrFail();
        $invite->update(['status' => 'declined']);
        
        return redirect()->route('login')->with('status', 'Invitation declined successfully.');
    }

    public function updateRole(Request $request, Invite $invite)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $invite->role_id = $request->role_id;
        $invite->save();

        $user = User::where('email', $invite->email)->first();
        if ($user) {
            $user->role_id = $invite->role_id;
            $user->save();
        }

        return redirect()->back()->with('success', 'Role updated for invite and user (if registered).');
    }
}
