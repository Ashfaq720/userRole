<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    public function readBlog (Request $request)
    {
        return response()->json(Post::all());
    }

    public function createBlog(Request $request){

        $data = $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Post created successfully']);
    }

    public function deleteBlog($id){

        $post = Post::find($id);
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function updateBlog (Request $request, $id){
        $user_id=Auth::id();
        $post = Post::find($id);
        $data = $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $post->update([
            'title' => $data['title'],
            'content' => $data['content'],           
        ]);

        return response()->json(['message' => 'Post updated successfully']);
    }

}