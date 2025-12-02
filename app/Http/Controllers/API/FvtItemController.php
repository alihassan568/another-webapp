<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FvtItem;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class FvtItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itemIds = FvtItem::where('user_id', Auth::id())->pluck('item_id')->toArray();

        $items = Item::whereIn('id', $itemIds)
        ->orderBy('id', 'desc')
        ->get();

        return $this->success($items, 'items fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required'
        ]);

        $result = FvtItem::where('item_id', '=' ,$validated['item_id'])->where('user_id','=', Auth::id())->first();

        if(empty($result)) {
            FvtItem::create([
                'item_id' => $validated['item_id'],
                'user_id' => Auth::id()
            ]);

            return $this->success([], 'item add to wishlist successfully');
        }

        return $this->error('item already added to wishlist', 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = FvtItem::where('item_id','=',$id)->where('user_id',Auth::id())->first();

        if(!empty($item)) {
            $item->delete();
        }

        return $this->success([], 'item removed from wishlist successfully');
    }
}
