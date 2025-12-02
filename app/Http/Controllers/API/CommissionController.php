<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\CommissionSetting;

class CommissionController extends Controller
{
    public function getItemCommissionRequest() 
    {
        $items = Item::where('user_id','=',Auth::id())
        ->where('commission_status','=','pending')
        ->where('requested_commission','>',0)
        ->select('id','name','category','sub_category','price','requested_commission')
        ->get();

        return $this->success($items, 'commission requests fetched successfully'); 
    }

    public function handleItemCommissionRequest(Request $request) 
    {
        $item = Item::where('id','=',$request->item_id)->first();

        if($request->status == 'rejected') {
            $item->commission_status = $request->status;
        }

        if($request->status == 'approved') {
            $item->commission_status = $request->status;
            $item->commission = $item->requested_commission;
        }

        $item->save();

        return $this->success($item, 'commission requests processed successfully');
    }

    public function getDefaultCommission()
    {
        $settings = CommissionSetting::getSettings();

        return $this->success([
            'rate' => $settings->rate,
            'active' => $settings->active,
        ], 'Default commission fetched successfully');
    }

    public function updateDefaultCommission(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0|max:100',
            'active' => 'sometimes|boolean',
        ]);

        $settings = CommissionSetting::getSettings();
        $settings->rate = $request->rate;

        if ($request->has('active')) {
            $settings->active = $request->boolean('active');
        }

        $settings->save();

        return $this->success($settings, 'Default commission updated successfully');
    }
}
