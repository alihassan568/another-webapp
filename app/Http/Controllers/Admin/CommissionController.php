<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Traits\TracksAdminActivity;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommissionRequestMail;
use Illuminate\Support\Facades\Gate;

class CommissionController extends Controller
{
    use TracksAdminActivity;
    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $item = Item::findOrFail($id);
        return view('admin.items.item-commission', compact('item'));
    }

    /**
     * Store a newly created resource in storage.
     * Sets the requested commission and marks status as pending for vendor approval
     */
    public function store(Request $request, $id)
    {
        Gate::authorize('setCommission', Item::class);
        $request->validate([
            'commission' => 'required|numeric|min:0|max:100',
        ]);

        $item = Item::findOrFail($id);

        // Set requested commission and mark as pending vendor approval
        $item->requested_commission = $request->commission;
        $item->commission_status = 'pending';
        $item->save();

        // Log the commission setting activity
        $this->logCommissionSet($item->id, $item->name, $request->commission, $item->user_id);

        \Log::info('Admin set commission request', [
            'admin_id' => auth()->id(),
            'item_id' => $item->id,
            'item_name' => $item->name,
            'requested_commission' => $request->commission,
            'vendor_id' => $item->user_id,
        ]);

        return redirect()->route('admin.items')->with('success', 'Commission request sent to vendor for approval!');
    }
}
