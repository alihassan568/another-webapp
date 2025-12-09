<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Mail\ItemAcceptRejectMail;
use App\Http\Resources\ItemResource;
use Illuminate\Support\Facades\Mail;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters from request
        $status = $request->query('status', 'all');
        $search = $request->query('search', '');
        $dateFrom = $request->query('date_from', '');
        $dateTo = $request->query('date_to', '');

        // Build query with all filters
        $items = Item::with('user')
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('sub_category', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($subQuery) use ($search) {
                          $subQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%")
                                   ->orWhere('business_name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        // Transform items using resource
        $items->getCollection()->transform(function ($item) {
            return (new ItemResource($item))->toArray(request());
        });

        // Append query parameters to pagination links
        $items->appends([
            'status' => $status,
            'search' => $search,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]);

        return view('admin.items.index', compact('items', 'status'));
    }

    public function search(Request $request)
    { 
        // Redirect to index with query parameters instead
        return redirect()->route('admin.items', [
            'status' => $request->status ?? 'all',
            'search' => $request->search ?? '',
            'date_from' => $request->date_from ?? '',
            'date_to' => $request->date_to ?? '',
        ]);
    }

    /**
     * Show detailed view of an item
     */
    public function show($id)
    {
        $item = Item::with('user')->findOrFail($id);
        
        // Transform item using resource
        $itemData = (new ItemResource($item))->toArray(request());
        
        return view('admin.items.show', compact('itemData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function accept($id)
    {
        $item = Item::find($id);

        if (!empty($item)) {
            $item->status = 'approved';
            $item->save();
        }

        $user = User::where('id','=',$item->user_id)->first();

        Mail::to($user->email)->queue(new ItemAcceptRejectMail($item,'approved'));

        return redirect()->route('admin.items')->with('success', 'Item approved successfully!');
    }

    public function reject_item($id)
    {
        return view('admin.items.item-reason',compact('id'));
    }

    public function reject(Request $request,$id)
    {
        $item = Item::find($id);

        if (!empty($item)) {
            $item->status = 'rejected';
             $item->rejection_reason = $request->rejection_reason;
            $item->save();
        }

        $user = User::where('id','=',$item->user_id)->first();

        Mail::to($user->email)->queue(new ItemAcceptRejectMail($item,'rejected'));

        return redirect()->route('admin.items')->with('success', 'Item rejected successfully!');
    }
}
