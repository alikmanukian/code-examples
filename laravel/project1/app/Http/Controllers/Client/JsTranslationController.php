<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Translate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use function base_path;
use function config;
use function response;

class JsTranslationController extends Controller
{
    public function __invoke()
    {
        $strings = Translate::getTranslations();

        $encoded = json_encode($strings,  JSON_THROW_ON_ERROR | JSON_UNESCAPED_LINE_TERMINATORS
            | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);

        $content = 'window.i18n = ' . $encoded . ';';
        return response($content)->header('Content-Type', 'text/javascript');
    }
}
