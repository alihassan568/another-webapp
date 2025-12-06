<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteRequest;
use App\Http\Resources\InviteResource;
use App\Mail\InviteUserMail;
use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class InviteController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Invite::class);
        $invites = Invite::with('role')->latest()->paginate(20);
        return Inertia::render('Admin/Invite/Index', [
            'invites' => InviteResource::collection($invites),
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Invite::class);
        $roles = Role::select('id', 'name')->get();
        return Inertia::render('Admin/Invite/Create', [
            'roles' => $roles->map(function($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                ];
            }),
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
        Mail::to($request->email)->send(new InviteUserMail($invite, $subject, $message));

        return redirect()->route('invite.index');
    }

    public function accept($token)
    {
        $invite = Invite::where('token', $token)->where('status', 'pending')->firstOrFail();
        return Inertia::render('Auth/Register', [
            'invite' => InviteResource::make($invite),
        ]);
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
