<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PostResource;
use App\Http\Resources\Admin\PostsResource;
use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        return Inertia::render('Posts/Index',[
            'data' => PostsResource::collection(
                Post::query()
                    ->with('qrCode')
                    ->paginate(20)
                    ->onEachSide(1)
                    ->withQueryString()
            )
        ]);
    }

    public function show(Post $post)
    {
      return Inertia::render('Posts/Show', [
          'data' => new PostResource($post)
      ]);
    }
}
