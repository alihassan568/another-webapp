<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Models\CommissionSetting;

class CommissionController extends Controller
{
    /**
     * Get all pending commission requests for the authenticated vendor
     */
    public function getItemCommissionRequest() 
    {
        try {
            $items = Item::where('user_id', Auth::id())
                ->where('commission_status', 'pending')
                ->where('requested_commission', '>', 0)
                ->select(
                    'id',
                    'name',
                    'category',
                    'sub_category',
                    'price',
                    'commission',
                    'requested_commission',
                    'commission_status',
                    'image',
                    'description',
                    'created_at',
                    'updated_at'
                )
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Commission requests fetched', [
                'vendor_id' => Auth::id(),
                'requests_count' => $items->count()
            ]);

            return $this->success($items, 'Commission requests fetched successfully'); 
        } catch (\Exception $e) {
            Log::error('Failed to fetch commission requests', [
                'vendor_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return $this->error('Failed to fetch commission requests', 500);
        }
    }

    /**
     * Handle commission request (approve/reject)
     */
    public function handleItemCommissionRequest(Request $request) 
    {
        try {
            // Validate request
            $validated = $request->validate([
                'item_id' => 'required|integer|exists:items,id',
                'status' => 'required|in:approved,rejected'
            ]);

            // Find item and verify ownership
            $item = Item::where('id', $request->item_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$item) {
                return $this->error('Item not found or unauthorized', 404);
            }

            // Check if already processed
            if ($item->commission_status !== 'pending') {
                return $this->error('This commission request has already been processed', 400);
            }

            // Process based on status
            if ($request->status === 'rejected') {
                $item->commission_status = 'rejected';
                $item->requested_commission = 0; // Reset requested commission
                $message = 'Commission request rejected successfully';
            } elseif ($request->status === 'approved') {
                $item->commission_status = 'approved';
                $item->commission = $item->requested_commission;
                $message = 'Commission request approved successfully';
            }

            $item->save();

            Log::info('Commission request processed', [
                'vendor_id' => Auth::id(),
                'item_id' => $item->id,
                'status' => $request->status,
                'commission' => $item->commission
            ]);

            return $this->success([
                'id' => $item->id,
                'name' => $item->name,
                'commission' => $item->commission,
                'commission_status' => $item->commission_status,
                'requested_commission' => $item->requested_commission
            ], $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error($e->errors(), 422);
        } catch (\Exception $e) {
            Log::error('Failed to process commission request', [
                'vendor_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return $this->error('Failed to process commission request', 500);
        }
    }

    public function getDefaultCommission()
    {
        $settings = CommissionSetting::getSettings();

        return $this->success([
            'rate' => $settings->rate,
            'active' => $settings->active,
        ], 'Default commission fetched successfully');
    }

    public function updateDefaultCommission(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0|max:100',
            'active' => 'sometimes|boolean',
        ]);

        $settings = CommissionSetting::getSettings();
        $settings->rate = $request->rate;

        if ($request->has('active')) {
            $settings->active = $request->boolean('active');
        }

        $settings->save();

        return $this->success($settings, 'Default commission updated successfully');
    }
}
