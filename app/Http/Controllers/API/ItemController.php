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
                    
                    $name = time() . '_' . $i . '_' . rand(1000, 9999);
                    $extension = $p_image->extension();
                    $fileName = md5($name . $p_image->getClientOriginalName());
                    $fullPath2 = $fileName . '.' . $extension;
                    
                    $uploadPath = public_path('storage/images/items');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    $p_image->move($uploadPath, $fullPath2);
                    $imagePaths[] = 'storage/images/items/' . $fullPath2;
                    
                    \Log::info('✅ ItemController: Image ' . $i . ' uploaded successfully');
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
                        
                        $name = time() . '_' . $i . '_' . rand(1000, 9999);
                        $extension = $p_image->extension();
                        $fileName = md5($name . $p_image->getClientOriginalName());
                        $fullPath2 = $fileName . '.' . $extension;
                        
                        // Ensure directory exists
                        $uploadPath = public_path('storage/images/items');
                        if (!file_exists($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }
                        
                        $p_image->move($uploadPath, $fullPath2);
                        $newImages[] = 'storage/images/items/' . $fullPath2;
                        
                        \Log::info('✅ ItemController: Image ' . $i . ' uploaded successfully');
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
