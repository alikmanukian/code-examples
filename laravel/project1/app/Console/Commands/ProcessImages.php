<?php

namespace App\Console\Commands;

use finfo;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Input\InputArgument;

class ProcessImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:prepare {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare images';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $code = $this->argument('code');

        foreach($this->getFiles($code) as $file) {
            $this->processImage($file);
        }

        return Command::SUCCESS;
    }

    protected function getFiles(string $code): array
    {
        $it = new RecursiveDirectoryIterator(public_path("images/posts/{$code}"));
        $it = new RecursiveIteratorIterator($it);
        $files = [];

        foreach ($it as $file) {
            if (is_dir($file) || Str::contains($file, '/thumb/')) {
                continue;
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file);

            if (!Str::startsWith($mime, 'image/')) {
                continue;
            }

            $files[] = $file->getPathname();
        }

        return $files;
    }

    protected function processImage(string $file)
    {
        $filename = basename($file);
        $path = pathinfo($file, PATHINFO_DIRNAME);

        if (!is_dir("{$path}/thumb")) {
            if (!mkdir("{$path}/thumb", 0755) && !is_dir("{$path}/thumb")) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', "{$path}/thumb"));
            }
        }

        $img = Image::make($file);

        [$width, $height] = $this->getSizes($img, 372);

        $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save("{$path}/thumb/{$filename}");
    }

    protected function getSizes(\Intervention\Image\Image $img, $boundarySize): array
    {
        $height = $img->height();
        $width = $img->width();

        if ($height > $width) {
            return [$boundarySize, null];
        }

        if ($width > $height) {
            return [null, $boundarySize];
        }

        return [$boundarySize, $boundarySize];
    }

}


