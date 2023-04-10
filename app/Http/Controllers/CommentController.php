<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

use function GuzzleHttp\Promise\all;

// 10 APR 2023 - COMMENT FEATURE
class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comments_content' => 'required'
        ]);

        $request['user_id'] = auth()->user()->id;
        $comment = Comment::create($request->all());

        // return response()->json($comment); #only load data and user id
        // return new CommentResource($comment->loadMissing(['comentator'])); #loads everything
        return new CommentResource($comment->loadMissing(['comentator:id,username'])); #loads only id and username
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'comments_content' => 'required',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update($request->only('comments_content'));

        return new CommentResource($comment->loadMissing(['comentator:id,username']));
    }

    public function delete($id)
    {
        $comment = Comment::findOrFail($id); #fetch comment id
        $comment->delete(); #delete comment
        
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
