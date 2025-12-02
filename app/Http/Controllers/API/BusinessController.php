<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessProfile;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BusinessController extends Controller
{
    public function getBusiness(Request $request)
    {
        Log::info('getBusiness called', $request->all());
        $data = [];
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');
        $radius = $request->query('radius');
        $businessType = $request->query('business_type');

        $response = $this->getUsersNearby($latitude, $longitude, $radius);
        Log::info('Users nearby found', ['count' => count($response)]);

        $filtered = [];

        foreach ($response as $d) {
            $business = BusinessProfile::where('user_id', '=', $d->id)->first();
            if (!$business) {
                Log::info('No business profile for user', ['user_id' => $d->id]);
            }

            $d->business_type = $business->business_type ?? null;
            $d->owner_name = $business->owner_name ?? null;
            $d->opening_time = $business->opening_time ?? null;
            $d->close_time = $business->close_time ?? null;
            $d->bank_title = $business->bank_title ?? null;
            $d->bank_name = $business->bank_name ?? null;
            $d->iban = $business->iban ?? null;
            $d->image = $business->image ?? null;

            // Apply filter if business_type is specified
            if (!$businessType || ($business && $business->business_type === $businessType)) {
                $filtered[] = $d;
            }
        }
        Log::info('Filtered businesses', ['count' => count($filtered)]);

        foreach ($filtered as $filter) {

            $category = $request->query('category');
            $sub_category = $request->query('sub_category');

            $result = Item::with(['comments.user'])
                ->where('user_id', $filter->id)
                ->where('status', '=', 'approved')
                ->when($category, function ($query, $category) {
                    return $query->where('category', $category);
                })
                ->when($sub_category, function ($query, $sub_category) {
                    return $query->where('sub_category', $sub_category);
                })
                ->get();

            Log::info('Items found for business', ['business_id' => $filter->id, 'item_count' => count($result)]);

            $response = [
                'business' => $filter,
                'items' => $result,
            ];

            $data[] = $response;
        }

        return $this->success($data, 'businesses fetched successfully');
    }

    public function getUsersNearby($latitude, $longitude, $radiusInKm = 10)
    {
        return DB::table('users')
            ->whereNotNull('email_verified_at')
            ->get();

        /* 
        // Original Logic
        return DB::table('users')
            ->select('*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->whereNotNull('email_verified_at')
            ->having('distance', '<=', $radiusInKm)
            ->orderBy('distance', 'asc')
            ->get();
        */
    }

    public function getBusinessItems(Request $request)
    {
        $vender_id = $request->query('vender_id');
        $category = $request->query('category');
        $sub_category = $request->query('sub_category');

        $result = Item::with(['comments.user'])
            ->where('user_id', $vender_id)
            ->where('status', '=', 'approved')
            ->when($category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($sub_category, function ($query, $sub_category) {
                return $query->where('sub_category', $sub_category);
            })
            ->get();

        return $this->success($result, 'items fetched successfully');
    }
}
