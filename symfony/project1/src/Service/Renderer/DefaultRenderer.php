<?php

declare(strict_types=1);

namespace App\Service\Renderer;

use Symfony\Component\HttpFoundation\Response;

class DefaultRenderer implements RendererInterface
{
    public function render(mixed $data): Response
    {
        return new Response($data);
    }

    public function error(string $message, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): Response
    {
        return new Response($message, $code);
    }
}