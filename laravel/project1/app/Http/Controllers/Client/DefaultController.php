<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\PostsResource;
use App\Models\Post;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class DefaultController extends Controller
{
    public function __invoke()
    {
        $posts = Post::query()
            ->with('qrCode')
            ->active()
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('web.homepage', compact('posts'));
    }
}
