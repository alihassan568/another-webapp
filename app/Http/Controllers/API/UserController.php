<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BusinessProfile;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserProfile()
    {
        $user = User::where('id', Auth::id())->first();

        return $this->success([
            'user' => $user
        ], 'Profile data fetched successfully!');
    }

    public function getVenderProfile()
    {
        $user = User::where('id', Auth::id())->first();

        $business = BusinessProfile::where('user_id', '=', Auth::id())->first();


        $user->business_type = $business->business_type ?? null;
        $user->owner_name = $business->owner_name ?? null;
        $user->opening_time = $business->opening_time ?? null;
        $user->close_time = $business->close_time ?? null;
        $user->bank_title = $business->bank_title ?? null;
        $user->bank_name = $business->bank_name ?? null;
        $user->iban = $business->iban ?? null;

        return $this->success([
            'user' => $user
        ], 'Profile data fetched successfully!');
    }

    public function updateUserProfile(Request $request) 
    {
        $user = User::where('id', Auth::id())->first();

        if (!empty($user)) {
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;

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
                $user->image = 'storage/images/users/' . $fullPath2;
            }
            $user->save();
        }

        return $this->success([
            'user' => $user
        ], 'Profile data updated successfully!');
    }

    public function updateVenderProfile(Request $request)
    {
        $user = User::where('id', Auth::id())->first();

        if (!empty($user)) {
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;

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
                $user->image = 'storage/images/vender/' . $fullPath2;
            }
            $user->save();
        }

        $business = BusinessProfile::where('user_id', '=', Auth::id())->first();

        if (!empty($business)) {
            $business->business_type = $request->business_type;
            $business->owner_name = $request->owner_name;
            $business->opening_time = $request->opening_time;
            $business->close_time = $request->close_time;
            $business->bank_title = $request->bank_title;
            $business->bank_name = $request->bank_name;
            $business->iban = $request->iban;
            $business->save();
        }

        $user->business_type = $business->business_type ?? null;
        $user->owner_name = $business->owner_name ?? null;
        $user->opening_time = $business->opening_time ?? null;
        $user->close_time = $business->close_time ?? null;
        $user->bank_title = $business->bank_title ?? null;
        $user->bank_name = $business->bank_name ?? null;
        $user->iban = $business->iban ?? null;

        return $this->success([
            'user' => $user
        ], 'Profile data updated successfully!');
    }
}
