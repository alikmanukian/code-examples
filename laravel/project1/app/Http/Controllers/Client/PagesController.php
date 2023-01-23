<?php

namespace App\Http\Controllers\Client;

use App\Facades\Seo;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class PagesController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->with('qrCode')
            ->orderBy('title')
            ->active()
            ->get();

        return view('web.pages', compact('posts'));
    }

    public function show(Request $request, string $code)
    {
        $post = Post::query()
            ->whereHas('qrCode', function(Builder $query) use ($code) {
                $query->where('code', $code);
            })
            ->firstOrFail();

        Seo::setType('article')
            ->setTitle($post->title)
            ->setDescription($post->excerpt ?? config('meta_tags.description.default'))
            ->setImage($post->share_image)
            ->addKeywords([$post->title]);

        return view('web.page', [
            'content' => Blade::render(
                app()->isLocal() && $request->debug
                    ? file_get_contents(
                        resource_path("views/posts/{$code}.blade.php")
                    )
                    : $post->content,
                [
                    'code' => $code,
                    'title' => $post->title,
                    'dates' => $post->dates,
                    'map' => $post->map,
                    'tomb' => [
                        'src' => asset("images/posts/{$code}/tomb.jpg"),
                        'srcThumb' => asset("images/posts/{$code}/thumb/tomb.jpg")
                    ]
                ]
            )
        ]);
    }
}
