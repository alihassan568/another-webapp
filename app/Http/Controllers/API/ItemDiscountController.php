<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ItemDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function add_discount(Request $request)
    {
        $item = Item::where('id','=',$request->id)->first();

        if(!empty($item)) {
            $item->discount_percentage = $request->discount_percentage;
            $item->valid_from = $request->valid_from;
            $item->valid_until = $request->valid_until;
            $item->save();
        }

        return $this->success($item, 'discount added to item successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function remove_discount($id)
    {
        $item = Item::where('id','=',$id)->first();

        if(!empty($item)) {
            $item->discount_percentage = 0.00;
            $item->valid_from = null;
            $item->valid_until = null;
            $item->save();
        }

        return $this->success($item, 'discount removed from item successfully');
    }
}
