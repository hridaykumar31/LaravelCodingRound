<?php

namespace App\Http\Controllers\Api;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
   public function index() {
     return response()->json(Post::all());
   }
   public function store(Request $request) {

    // use validate function to cheak each of the input is valid or not
      $validate_data = $request->validate([
        'title' => 'required|string',
        'content' => 'required|string',
        'tag_ids' => 'required|array',
        'tag_ids.*' => 'exists:tags,id'
      ]);


    //store all the input data into the db after validation
    $post = Post::create([
        'title' => $request->title,
        'content' => $request->content
    ]);
    $post->tags()->attach($validate_data['tag_ids']);

      return response()->json([
        'message' => 'Post Created Successfully',
        'post' => $validate_data
      ], 201);
   }
   public function show($id) {

    //find single post based on the primary key id and run a query to fetch the single from database
     $single_post = Post::findorFail($id);

     return response()->json($single_post,);
   }
   public function update(Request $request, $id) {
    $post = Post::findorFail($id);
    $post->update([
       'title' => $request->title,
       'content' => $request->content
    ]);

    return response()->json([
      'message' => 'Post updated successfully',
    ], 200);
   }
    public function destroy($id) {
    $post = Post::findorFail($id);
    $post->delete();

    return response()->json([
      'message' => 'Post deleted successfully',
    ], 200);
  }
}
