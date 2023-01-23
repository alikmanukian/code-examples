<?php

namespace App\Console\Commands;

use App\Models\Post;
use finfo;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PublishPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish {code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish posts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$code = $this->argument('code')) {
            $codes = $this->getCodes();
        } else {
            $codes = [$code];
        }

        $oldUrl =  config('app.asset_url');
        config(['app.asset_url' => config('app.do_asset_url')]);

        $posts = Post::query()
            ->with('qrCode')
            ->whereHas('qrCode', function (Builder $query) use ($codes) {
                $query->whereIn('code', $codes);
            })
            ->get()
            ->each(function (Post $post) {
                $post->content = $this->getRenderedPostContent($post);
                $post->save();
            });

        config(['app.asset_url' => $oldUrl]);


        return Command::SUCCESS;
    }

    private function getRenderedPostContent(Post $post): string
    {
        return Blade::render(
            file_get_contents(
                resource_path("views/posts/{$post->qrCode->code}.blade.php")
            ),
            [
                'code' => $post->qrCode->code,
                'title' => $post->title,
                'dates' => $post->dates,
                'map' => $post->map,
                'tomb' => [
                    'src' => asset("images/posts/{$post->qrCode->code}/tomb.jpg"),
                    'srcThumb' => asset("images/posts/{$post->qrCode->code}/thumb/tomb.jpg")
                ]
            ]
        );
    }

    private function getCodes(): array
    {
        $files = glob(resource_path("views/posts/*.php"));
        return array_map(function ($file) {
            return preg_replace("#\.blade\.php$#", "", basename($file));
        }, $files);
    }
}
