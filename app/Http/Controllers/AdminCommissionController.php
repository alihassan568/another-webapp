<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommissionSetting;
use Illuminate\Support\Facades\Gate;

class AdminCommissionController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', CommissionSetting::class);
        $settings = CommissionSetting::getSettings();
        return view('admin.commission', compact('settings'));
    }

    public function update(Request $request)
    {
        Gate::authorize('update', CommissionSetting::class);

        $request->validate([
            'default_commission' => 'required|numeric|min:0|max:100',
            'stripe_fee_percentage' => 'required|numeric|min:0|max:100',
            'stripe_fee_fixed' => 'required|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $settings = CommissionSetting::getSettings();
        $settings->rate = $request->default_commission;
        $settings->stripe_fee_percentage = $request->stripe_fee_percentage;
        $settings->stripe_fee_fixed = $request->stripe_fee_fixed;
        $settings->tax_percentage = $request->tax_percentage ?? 0.00;
        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
