<?php

declare(strict_types=1);

namespace App\Service\Renderer;

use Symfony\Component\HttpFoundation\Response;

interface RendererInterface
{
    public function render(mixed $data): Response;

    public function error(string $message, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): Response;
}