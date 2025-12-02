<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required',
            'item_id' => 'required'
        ]);

        $item = Comment::create([
            'message' => $validated['name'],
            'item_id' => $validated['category'],
            'user_id' => Auth::id()
        ]);

        return $this->success($item, 'commented on item successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Comment::where('id','=',$id)->first();

        if(!empty($item)){

            $validated = $request->validate([
                'message' => 'required'
            ]);

            $item->message = $validated['message'];
            $item->save();
        }

        return $this->success($item, 'item comment updated successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Comment::find($id);
        if (!$item) {
            return $this->error('Item not found', 404);
        }

        if($item->user_id == Auth::id()) {
            $item->delete();
            return $this->success([], 'Item deleted');
        }else{
            return $this->error('You can not delete this comment', 403);
        }
    }
}
