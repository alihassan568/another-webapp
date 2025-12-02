<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Mail\ItemCreatedMail;
use Illuminate\Support\Facades\Mail;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category = $request->query('category');
        $sub_category = $request->query('sub_category');

        $items = Item::with(['comments.user'])
        ->where('user_id', Auth::id())
        ->where('status','=','approved')
        ->when($category, function ($query,$category) {
            $query->where('category', $category);
        })
        ->when($sub_category, function ($query,$sub_category) {
            $query->where('sub_category', $sub_category);
        })
        ->orderBy('id', 'desc')
        ->get();

        $categories = Category::where('user_id',Auth::id())->get();

        $response = [
            'items' => $items,
            'categories' => $categories,
        ];

        return $this->success($response, 'items fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $path = null;

        $validated = $request->validate([
            'name' => 'required',
            'category' => 'required',
            'sub_category' => 'required',
            'price' => 'required'
        ]);

        if($request->hasFile('image')) {
            // upload photo
            $p_image = $request->image;
            $name = time();
            $file = $p_image->getClientOriginalName();
            $extension = $p_image->extension();
            $ImageName = $name.$file;
            $fileName = md5($ImageName);
            $fullPath2 = $fileName.'.'.$extension;
            $p_image->move(public_path('storage/images/items'), $fullPath2);
            $path = 'storage/images/items/'.$fullPath2;
        }

        $item = Item::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'sub_category' => $validated['sub_category'],
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $validated['price'],
            'image' => $path,
            'discount_percentage' => $request->discount_percentage,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'pickup_start_time' => $request->pickup_start_time,
            'pickup_end_time' => $request->pickup_end_time,
            'user_id' => Auth::id()
        ]);

        $user = User::where('id','=',Auth::id())->first();

        Mail::to(env('ADMIN_EMAIL'))->queue(new ItemCreatedMail($item,$user->name));

        return $this->success($item, 'item created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::where('id','=',$id);

        return $this->success($item, 'item fetched successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::where('id','=',$id)->first();

        if(!empty($item)){

            $validated = $request->validate([
                'name' => 'required',
                'category' => 'required',
                'sub_category' => 'required',
                'price' => 'required'
            ]);

            $item->name = $validated['name'];
            $item->category = $validated['category'];
            $item->sub_category = $validated['sub_category'];
            $item->description = $request->description;
            $item->quantity = $request->quantity;
            $item->discount_percentage = $request->discount_percentage;
            $item->valid_from = $request->valid_from;
            $item->valid_until = $request->valid_until;
            $item->pickup_start_time = $request->pickup_start_time;
            $item->pickup_end_time = $request->pickup_end_time;
            $item->price = $validated['price'];

            if($request->hasFile('image')) {
                // upload photo
                $p_image = $request->image;
                $name = time();
                $file = $p_image->getClientOriginalName();
                $extension = $p_image->extension();
                $ImageName = $name.$file;
                $fileName = md5($ImageName);
                $fullPath2 = $fileName.'.'.$extension;
                $p_image->move(public_path('storage/images/items'), $fullPath2);
                $item->image = 'storage/images/items/'.$fullPath2;
            }

            $item->save();
        }

        return $this->success($item, 'item updated successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::find($id);
        if (!$item) {
            return $this->error('Item not found', 404);
        }

        $item->delete();

        return $this->success([], 'Item deleted');
    }
}
