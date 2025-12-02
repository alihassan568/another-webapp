<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommissionRequestMail;

class CommissionController extends Controller
{
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
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'commission' => 'required|numeric|min:0|max:100',
        ]);

        $item = Item::find($id);

        if (!empty($item)) {
            $item->commission = $request->commission;
            $item->save();
        }

        return redirect()->route('admin.items')->with('success', 'Commission set successfully!');
    }
}
