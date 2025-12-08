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

        // Include both approved AND pending items for vendors to see their submissions
        $items = Item::with(['comments.user'])
        ->where('user_id', Auth::id())
        ->whereIn('status', ['approved', 'pending'])
        ->when($category, function ($query,$category) {
            $query->where('category', $category);
        })
        ->when($sub_category, function ($query,$sub_category) {
            $query->where('sub_category', $sub_category);
        })
        ->orderBy('id', 'desc')
        ->get();

        \Log::info('Items fetched for vendor', [
            'vendor_id' => Auth::id(),
            'total_items' => $items->count(),
            'approved' => $items->where('status', 'approved')->count(),
            'pending' => $items->where('status', 'pending')->count()
        ]);

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
        \Log::info('📥 ItemController: store() called', [
            'vendor_id' => Auth::id(),
            'has_image' => $request->hasFile('image'),
            'request_data' => $request->except(['image'])
        ]);

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
            \Log::info('📸 ItemController: Image uploaded', ['path' => $path]);
        }

        \Log::info('💾 ItemController: Creating item in database...');

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

        \Log::info('✅ ItemController: Item created successfully', [
            'item_id' => $item->id,
            'item_name' => $item->name,
            'status' => $item->status,
            'price' => $item->price
        ]);

        try {
            $adminEmail = env('ADMIN_EMAIL');
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                $user = User::where('id', '=', Auth::id())->first();
                Mail::to($adminEmail)->queue(new ItemCreatedMail($item, $user->name));
                \Log::info('📧 ItemController: Email queued', ['to' => $adminEmail, 'vendor' => $user->name]);
            } else {
                \Log::warning('⚠️ ItemController: ADMIN_EMAIL not configured or invalid');
            }
        } catch (\Exception $e) {
            \Log::warning('❌ ItemController: Failed to send email: ' . $e->getMessage());
        }

        \Log::info('🎉 ItemController: Returning success response', ['item_id' => $item->id]);
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
        \Log::info('📝 ItemController: update() called', [
            'item_id' => $id,
            'vendor_id' => Auth::id(),
            'has_image' => $request->hasFile('image')
        ]);

        $item = Item::where('id','=',$id)->first();

        if(!empty($item)){

            $validated = $request->validate([
                'name' => 'required',
                'category' => 'required',
                'sub_category' => 'required',
                'price' => 'required'
            ]);

            // TODO: Update all item fields including pickup times
            $item->name = $validated['name'];
            $item->category = $validated['category'];
            $item->sub_category = $validated['sub_category'];
            $item->description = $request->description;
            $item->quantity = $request->quantity;
            $item->discount_percentage = $request->discount_percentage ?? 0;
            $item->valid_from = $request->valid_from ?? 0;
            $item->valid_until = $request->valid_until ?? 0;
            $item->pickup_start_time = $request->pickup_start_time ?? 0;
            $item->pickup_end_time = $request->pickup_end_time ?? 0;
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
            \Log::info('✅ ItemController: Item updated successfully', ['item_id' => $item->id]);
        } else {
            \Log::warning('⚠️ ItemController: Item not found for update', ['item_id' => $id]);
        }

        return $this->success($item, 'item updated successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        \Log::info('🗑️ ItemController: destroy() called', ['item_id' => $id, 'vendor_id' => Auth::id()]);

        $item = Item::find($id);
        if (!$item) {
            \Log::warning('⚠️ ItemController: Item not found for deletion', ['item_id' => $id]);
            return $this->error('Item not found', 404);
        }

        $item->delete();
        \Log::info('✅ ItemController: Item deleted successfully', ['item_id' => $id]);

        return $this->success([], 'Item deleted');
    }
}
