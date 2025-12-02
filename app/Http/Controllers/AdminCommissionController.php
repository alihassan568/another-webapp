<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommissionSetting;

class AdminCommissionController extends Controller
{
    public function index()
    {
        $settings = CommissionSetting::getSettings();
        return view('admin.commission', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'default_commission' => 'required|numeric|min:0|max:100',
            'stripe_fee_percentage' => 'required|numeric|min:0|max:100',
            'stripe_fee_fixed' => 'required|numeric|min:0',
        ]);

        $settings = CommissionSetting::getSettings();
        
        // Update rate (which maps to default_commission in migration, but model logic handles it)
        $settings->rate = $request->default_commission;
        $settings->stripe_fee_percentage = $request->stripe_fee_percentage;
        $settings->stripe_fee_fixed = $request->stripe_fee_fixed;
        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
