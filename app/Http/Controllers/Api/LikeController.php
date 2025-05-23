<?php

namespace App\Http\Controllers\Api;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likePost($id) {
        $post = Post::findorFail($id); 
       // return response()->json("okk");

        //find the post
    //    $post = Post::findorFail($id);
    //     return response()->json([
    //         'message' => $post
    //     ], 200);
      $isTrue = $post->isLiked(auth()->user());
      
      if(!$isTrue) {
        $post->likes()->create(['user_id' => auth()->id()]);
        return response()->json([
          'message' => 'User liked the post successfully',
        ], 200);
      }

       return response()->json([
        'message' => 'User already liked the post',
       ], 200);
      
    }
    public function disLikePost($id) {

        //find the post
        $post = Post::findorFail($id);
        
        $post->likes()->where('user_id', auth()->id())->delete();

        return response()->json([
            'message' => 'User removed the like from post',
        ], 200);
    }


    //Handilng the function of like and dislike of the comment

     public function LikeComment($id) {
      $comment = Comment::find($id);
      if(!$comment) {
        return response()->json([
          'message' => 'Comment not found in db',
        ], 200, $headers);
      }
      $isTrue = $comment->isLiked(auth()->user());
      if(!$isTrue) {
        $comment->likes()->create([
          'user_id' => auth()->id()
        ]);

        return response()->json([
         'message' => 'User liked the comment successfully',
        ], 200);
      }


      return response()->json([
        'message' => 'User already liked the post',

      ], 200);
    }
    public function disLikeComment($id) {

        //find the post
        $comment = Comment::findorFail($id);
        
        $comment->likes()->where('user_id', auth()->id())->delete();

        return response()->json([
            'message' => 'User removed the like from comment',
        ], 200);
    }
    
} 