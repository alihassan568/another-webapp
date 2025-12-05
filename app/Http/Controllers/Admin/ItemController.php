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
        $status = $request->query('status', 'all');

        $items = Item::with('user')
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        // Transform items using resource
        $items->getCollection()->transform(function ($item) {
            return (new ItemResource($item))->toArray(request());
        });

        return view('admin.items.index', compact('items','status'));
    }

    public function search(Request $request)
    { 
        $req_status = $request->status;

        $items = Item::with('user')
        ->when($req_status !== 'all', function ($query) use ($req_status) {
            return $query->where('status', $req_status);
        })
        ->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        })
        ->when($request->filled('date_from'), function ($query) use ($request) {
            $query->whereDate('created_at', '>=', $request->date_from);
        })
        ->when($request->filled('date_to'), function ($query) use ($request) {
            $query->whereDate('created_at', '<=', $request->date_to);
        })
        ->orderBy('id', 'desc')
        ->paginate(20);

        $items->getCollection()->transform(function ($item) {
            return new ItemResource($item);
        });

        $status = 'Search Result';

        return view('admin.items.index', compact('items','status'));
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
