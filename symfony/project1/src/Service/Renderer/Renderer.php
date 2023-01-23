<?php

declare(strict_types=1);

namespace App\Service\Renderer;

use Symfony\Component\HttpFoundation\Request;

class Renderer
{
    public static function makeRenderer(Request $request, array $renderers = []): RendererInterface
    {
        $format = $request->getPreferredFormat();

        if (array_key_exists($format, $renderers)
            && class_exists($renderers[$format])
        ) {
            $class = new $renderers[$format];

            if ($class instanceof RendererInterface) {
                return $class;
            }
        }

        return new DefaultRenderer();
    }
}