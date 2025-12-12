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
    public function index(Request $request)
    {
        $category = $request->query('category');
        $sub_category = $request->query('sub_category');
        $is_surprise_bag = $request->query('is_surprise_bag');
        // Include both approved AND pending items for vendors to see their submissions
        $query = Item::with(['comments.user'])
             ->where('user_id', Auth::id())
             ->whereIn('status', ['approved', 'pending'])
             ->orderBy('id', 'desc');

        if ($category) {
            $query->where('category', $category);
        }

        if ($sub_category) {
            $query->where('sub_category', $sub_category);
        }

        if ($is_surprise_bag !== null) {
            $isSurprise = filter_var($is_surprise_bag, FILTER_VALIDATE_BOOLEAN);
            if ($isSurprise) {
                $query->isSurpriseBag();
            } else {
                $query->notSurpriseBag();
            }
        }
        
        $items = $query->get();

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

    public function store(Request $request)
    {
        \Log::info('📥 ItemController: store() called', [
            'vendor_id' => Auth::id(),
            'has_images' => $request->hasFile('images'),
            'images_count' => $request->hasFile('images') ? count($request->file('images')) : 0,
            'request_data' => $request->except(['images'])
        ]);

        $imagePaths = [];
        $maxImages = 10; // Maximum allowed images per item

        // Validate required fields first
        $validated = $request->validate([
            'name' => 'required',
            'category' => 'required',
            'sub_category' => 'required',
            'price' => 'required',
        ]);

        if($request->hasFile('images')) {
            $images = $request->file('images');
            if (!is_array($images)) {
                $images = [$images];
            }
            $imageCount = min(count($images), $maxImages);
            \Log::info('📸 ItemController: Processing ' . $imageCount . ' images');
            for($i = 0; $i < $imageCount; $i++) {
                try {
                    $p_image = $images[$i];
                    if (!$p_image->isValid()) {
                        \Log::warning('⚠️ ItemController: Image ' . $i . ' is invalid, skipping');
                        continue;
                    }
                    if ($p_image->getSize() > 5120 * 1024) {
                        \Log::warning('⚠️ ItemController: Image ' . $i . ' exceeds 5MB, skipping');
                        continue;
                    }
                    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (!in_array($p_image->getMimeType(), $allowedMimes)) {
                        \Log::warning('⚠️ ItemController: Image ' . $i . ' has invalid mime type: ' . $p_image->getMimeType());
                        continue;
                    }
                    // Use Laravel storage disk to save image
                    $path = $p_image->store('images/items', 'public');
                    $imagePaths[] = 'storage/' . $path;
                    \Log::info('✅ ItemController: Image ' . $i . ' uploaded successfully to ' . $path);
                } catch (\Exception $e) {
                    \Log::error('❌ ItemController: Failed to upload image ' . $i . ': ' . $e->getMessage());
                    continue;
                }
            }
            \Log::info('📸 ItemController: ' . count($imagePaths) . ' images uploaded successfully', ['paths' => $imagePaths]);
        }

        $path = !empty($imagePaths) ? json_encode($imagePaths) : null;

        \Log::info('💾 ItemController: Creating item in database...', ['image_count' => count($imagePaths)]);

        $item = Item::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'sub_category' => $validated['sub_category'],
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $validated['price'],
            'image' => $path, // JSON array of image paths
            'discount_percentage' => $request->discount_percentage,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'pickup_start_time' => $request->pickup_start_time,
            'pickup_end_time' => $request->pickup_end_time,
            'is_surprise_bag' => filter_var($request->is_surprise_bag, FILTER_VALIDATE_BOOLEAN),
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

    public function show(string $id)
    {
        $item = Item::where('id','=',$id);

        return $this->success($item, 'item fetched successfully');
    }

    public function update(Request $request, string $id)
    {
        \Log::info('📝 ItemController: update() called', [
            'item_id' => $id,
            'vendor_id' => Auth::id(),
            'has_images' => $request->hasFile('images'),
            'images_count' => $request->hasFile('images') ? count($request->file('images')) : 0,
            'existing_images' => $request->input('existing_images')
        ]);

        $item = Item::where('id','=',$id)->first();

        if(!empty($item)){
            $maxImages = 10; // Maximum allowed images per item

            // Validate required fields only
            $validated = $request->validate([
                'name' => 'required',
                'category' => 'required',
                'sub_category' => 'required',
                'price' => 'required',
                'existing_images' => 'nullable|string' // JSON string of existing images to keep
            ]);

            // Update all item fields
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
            $item->is_surprise_bag = filter_var($request->is_surprise_bag, FILTER_VALIDATE_BOOLEAN);
            $item->price = $validated['price'];

            // Handle images update
            $existingImages = [];
            
            // Get existing images that should be kept
            if($request->has('existing_images') && !empty($request->existing_images)) {
                $existingImages = json_decode($request->existing_images, true) ?? [];
                \Log::info('📸 ItemController: Keeping existing images', ['count' => count($existingImages)]);
            }

            // Upload new images with error handling
            if($request->hasFile('images')) {
                $newImages = [];
                $images = $request->file('images');
                
                // Ensure images is an array
                if (!is_array($images)) {
                    $images = [$images];
                }
                
                $remainingSlots = $maxImages - count($existingImages);
                $imageCount = min(count($images), $remainingSlots);
                
                \Log::info('📸 ItemController: Uploading new images', ['count' => $imageCount]);
                
                for($i = 0; $i < $imageCount; $i++) {
                    try {
                        $p_image = $images[$i];
                        
                        // Validate individual image
                        if (!$p_image->isValid()) {
                            \Log::warning('⚠️ ItemController: Image ' . $i . ' is invalid, skipping');
                            continue;
                        }
                        
                        // Check file size (5MB max)
                        if ($p_image->getSize() > 5120 * 1024) {
                            \Log::warning('⚠️ ItemController: Image ' . $i . ' exceeds 5MB, skipping');
                            continue;
                        }
                        
                        // Check mime type
                        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        if (!in_array($p_image->getMimeType(), $allowedMimes)) {
                            \Log::warning('⚠️ ItemController: Image ' . $i . ' has invalid mime type');
                            continue;
                        }
                        
                        // Use Laravel storage disk (same as store() method)
                        $path = $p_image->store('images/items', 'public');
                        $newImages[] = 'storage/' . $path;
                        
                        \Log::info('✅ ItemController: Image ' . $i . ' uploaded successfully to ' . $path);
                    } catch (\Exception $e) {
                        \Log::error('❌ ItemController: Failed to upload image ' . $i . ': ' . $e->getMessage());
                        continue;
                    }
                }
                
                // Merge existing and new images
                $allImages = array_merge($existingImages, $newImages);
                $item->image = json_encode($allImages);
                
                \Log::info('📸 ItemController: Images updated', [
                    'existing' => count($existingImages),
                    'new' => count($newImages),
                    'total' => count($allImages)
                ]);
            } elseif(!empty($existingImages)) {
                // Only existing images, no new uploads
                $item->image = json_encode($existingImages);
                \Log::info('📸 ItemController: Only existing images kept');
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
