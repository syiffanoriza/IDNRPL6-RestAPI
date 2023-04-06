<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isPostOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 06 APR
        $currentUser = Auth::User(); #cek user
        $post = Post::findOrFail($request->id); #cek id post

        if ($post->author != $currentUser->id) {
            return response()->json(['message' => 'data not found'], 404); #return 404 klo bukan post owner
        }
        return $next($request);
    }
}
