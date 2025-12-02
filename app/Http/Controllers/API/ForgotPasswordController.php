<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserOTP;
use App\Mail\OtpMail;
use App\Mail\VerificationMail;
use App\Models\EmailVerificationCode;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function sendOtpEmail(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();

        if (empty($user)) {
            return $this->error('User not Found!', 401);
        }

        $otp = random_int(100000, 999999);

        UserOTP::create([
            'otp' => $otp,
            'expired_at' => Carbon::now()->addMinutes(10),
            'user_id' => $user->id
        ]);

        Mail::to($request->email)->queue(new OtpMail($otp));

        return $this->success([], 'We’ve sent a One-Time Password (OTP) to your registered email address.Please check your email and use the OTP to reset your password');

        // return $this->error('item already added to wishlist', 401);
        // return $this->success($items, 'items fetched successfully');
    }

    public function verifyOtp(Request $request)
    {
        $otp = UserOTP::where('otp', '=', $request->otp)->first();

        if (empty($otp)) {
            return $this->error('Invalid OTP', 401);
        }

        if ($otp->expired_at->isPast()) {
            return $this->error('OTP has expired.', 401);
        }

        $token = Str::random(40);

        $otp->token = $token;
        $otp->save();

        $result = [
            'token' => $token
        ];

        return $this->success($result, 'OTP Verified successfully');
    }

    public function resetPassword(Request $request)
    {
        $otp = UserOTP::where('token', '=', $request->token)->first();

        if (empty($otp)) {
            return $this->error('Invalid Token', 401);
        }

        if ($request->new_password != $request->confirm_password) {
            return $this->error('The new password and its confirmation must match. Please try again!', 401);
        }

        $user = User::where('id', '=', $otp->user_id)->first();

        if (!empty($user)) {
            $user->password = $request->new_password;
            $user->save();
            $otp->delete();
        }

        return $this->success([], 'Your password has been changed successfully!');
    }

    public function emailVerification(Request $request)
    {
        $id = $request->query('id');
        $token = $request->query('token');

        $result = EmailVerificationCode::where('token', '=', $token)->where('user_id', '=', $id)->first();

        if (!empty($result)) {
            if (!$result->expired_at->isPast()) {
                User::where('id','=',$id)->update([
                    'email_verified_at' => now()
                ]);

                $result->delete();

                return view('auth.login');
            }
        }

        abort(404);
    }
}
