<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmailVerificationCode;
use App\Models\BusinessProfile;
use Illuminate\Support\Facades\Hash;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function user_signup(Request $request)
    {
        $path = null;
        // Manually check if email already exists
        if (User::where('email', $request->email)->exists()) {
            return $this->error('Email already exists.', 422);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        if ($request->hasFile('image')) {
            // upload photo
            $v_image = $request->image;
            $name = time();
            $file = $v_image->getClientOriginalName();
            $extension = $v_image->extension();
            $ImageName = $name . $file;
            $fileName = md5($ImageName);
            $fullPath2 = $fileName . '.' . $extension;
            $v_image->move(public_path('storage/images/users'), $fullPath2);
            $path = 'storage/images/users/' . $fullPath2;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'user',
            'phone' => $request->phone,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'image' => $path
        ]);

        // $token = $user->createToken('auth_token')->plainTextToken;

        $result = $this->sendEmailVerificationOtp($user->id,$user->email);

        return $this->success([], 'We’ve sent a One-Time Password (OTP) to your verifiy email address.Please check your email and use the OTP to verifiy your email');

        // return $this->success([
        //     'user' => $user,
        //     'token' => $token
        // ], 'User registered successfully');
    }

    public function user_login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if(empty($user)) {
            return $this->error('Invalid credentials', 401);
        }

        if (!$user->hasVerifiedEmail()) {
            $this->sendEmailVerificationOtp($user->id,$user->email);
            return $this->error('Your email address is not verified.Please check your email and use the OTP to verifiy your email', 403);
        }

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->error('Invalid credentials', 401);
        }

        if ($user->role != 'user') {
            return $this->error('Invalid credentials', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token
        ], 'Login successful');
    }

    public function vender_signup(Request $request)
    {
        $path = null;
        // Manually check if email already exists
        if (User::where('email', $request->email)->exists()) {
            return $this->error('Email already exists.', 422);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($request->hasFile('image')) {
            // upload photo
            $v_image = $request->image;
            $name = time();
            $file = $v_image->getClientOriginalName();
            $extension = $v_image->extension();
            $ImageName = $name . $file;
            $fileName = md5($ImageName);
            $fullPath2 = $fileName . '.' . $extension;
            $v_image->move(public_path('storage/images/vender'), $fullPath2);
            $path = 'storage/images/vender/' . $fullPath2;
        }

        // Extract country code from phone number
        $country = \App\Helpers\CountryHelper::extractCountryFromPhone($request->phone);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'business',
            'phone' => $request->phone,
            'country' => $country,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'image' => $path
        ]);

        if ($user) {
            $business = BusinessProfile::create([
                'business_type' => $request->business_type,
                'owner_name' => $request->owner_name,
                'opening_time' => $request->opening_time,
                'close_time' => $request->close_time,
                'user_id' => $user->id
            ]);

            $user->business_type = $business->business_type ?? null;
            $user->owner_name = $business->owner_name ?? null;
            $user->opening_time = $business->opening_time ?? null;
            $user->close_time = $business->close_time ?? null;
        }

        // $token = $user->createToken('auth_token')->plainTextToken;

        $this->sendEmailVerificationOtp($user->id,$user->email);

        return $this->success([], 'We’ve sent a One-Time Password (OTP) to your verifiy email address.Please check your email and use the OTP to verifiy your email');

        // return $this->success([
        //     'user' => $user,
        //     'token' => $token
        // ], 'User registered successfully');
    }

    public function vender_login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if(empty($user)) {
            return $this->error('Invalid credentials', 401);
        }

        if (!$user->hasVerifiedEmail()) {
            $this->sendEmailVerificationOtp($user->id,$user->email);
            return $this->error('Your email address is not verified.Please check your email and use the OTP to verifiy your email', 403);
        }

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->error('Invalid credentials', 401);
        }

        if ($user->role != 'business') {
            return $this->error('Invalid credentials 123', 401);
        }

        // Check if vendor account is blocked
        if ($user->blocked_at !== null) {
            return $this->error(
                'Your account has been blocked by the system administrator. Please contact support at info@anothergo.com for assistance.',
                403
            );
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $business = BusinessProfile::where('user_id', '=', $user->id)->first();


        $user->business_type = $business->business_type ?? null;
        $user->owner_name = $business->owner_name ?? null;
        $user->opening_time = $business->opening_time ?? null;
        $user->close_time = $business->close_time ?? null;
        $user->bank_title = $business->bank_title ?? null;
        $user->bank_name = $business->bank_name ?? null;
        $user->iban = $business->iban ?? null;

        return $this->success([
            'user' => $user,
            'token' => $token
        ], 'Login successful');
    }

    public function sendEmailVerificationOtp($userId,$email)
    {
        $token = Str::random(80);

        EmailVerificationCode::create([
            'token' => $token,
            'expired_at' => Carbon::now()->addMinutes(10),
            'user_id' => $userId
        ]);

        $link = env('APP_URL').'/email/verification'.'?id='.$userId.'&token='.$token;

        Mail::to($email)->queue(new VerificationMail($link));

        return true;
    }
}
