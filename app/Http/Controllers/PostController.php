<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        // 26 MAR 2023
        // $posts = Post::all();
        // return response()->json($posts);

        // 27 MAR 2023
        $posts = Post::all();
        // return response()->json(['data'=>$posts]);
        // return PostResource::collection($posts);
        // 10 APR 2023
        return PostDetailResource::collection($posts->loadMissing('writer:id,username', 'comments'));
    }

    //  -- 27 MAR 2023
    public function show($id)
    {
        // $post = Post::findOrFail($id);
        $post = Post::with('writer:id,username')->findOrFail($id);
        // return new PostDetailResource($post);
        // 10 APR 2023
        return new PostDetailResource($post->loadMissing('writer:id,username','comments:id,post_id,user_id,comments_content'));
    }

    public function show2($id)
    {
        $post = Post::findOrFail($id);
        return new PostDetailResource($post);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);

        // 11 APR 2023
        $image = null;
        if ($request->file) {
            $fileName = $this->generateRandomString(); #renames file
            $extension = $request->file->extension(); #stores file extension
            Storage::putFileAs('image', $request->file, $fileName.'.'.$extension);
        }

        $request['image'] = $image;

        $request['author'] = Auth::user()->id;
        
        $post = Post::create($request->all());
        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    // 06 APR
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id); #fetch post id
        $post->delete(); #delete post

        // return new PostDetailResource($post->loadMissing('writer:id,username')); #<--returns deleted post data
        return response()->json(['message' => 'Post deleted successfully']);
    }

    // 11 APR 2023
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
