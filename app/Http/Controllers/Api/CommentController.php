<?php

namespace App\Http\Controllers\Api;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function store(Request $request, $id) {

        $request->validate([
           'body' => 'required|string|max:1000'
        ]);

        $auth_id = auth()->id();
        
        $post = Post::findorFail($id);
        

        $comment = $post->comments()->create([
            'body' => $request->body,
            'user_id' => $auth_id,
        ]);
         
        

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment,
        ], 200);

    }
}
