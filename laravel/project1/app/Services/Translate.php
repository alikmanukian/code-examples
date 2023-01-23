<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Translate
{
    public static function getTranslations()
    {
        $lang = config('app.locale');

        $files = [
            base_path("lang/{$lang}/validation.php"),
            base_path("lang/{$lang}.json"),
        ];

        return Cache::remember(
            "lang_{$lang}.js",
            now()->addSeconds(1),
            static function () use ($lang, $files) : array {

                $strings = [];
                foreach ($files as $file) {
                    ['name' => $name, 'content' => $content] = self::getFileContent($file);

                    if (isset($name) && isset($content)) {
                        $strings[$name] = $content;
                        unset($name);
                    }

                    if (!empty($strings)) {
                        $strings['__possible_keys'] = array_keys($strings);
                    }

                }

                return $strings;
            });
    }

    private static function getFileContent(string $file): array
    {
        $content = null;
        $name = null;

        if (Str::endsWith($file, '.json')) {
            if (file_exists($file)) {
                $name = '__global';
                $content = json_decode(file_get_contents($file));
            }
        } else {
            if (file_exists($file)) {
                $name = basename($file, '.php');
                $content = require $file;
            }
        }

        return compact('name', 'content');
    }
}
