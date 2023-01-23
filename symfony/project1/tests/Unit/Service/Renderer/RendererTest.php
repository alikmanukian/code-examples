<?php

namespace Tests\Unit\Service\Renderer;

use App\Service\Renderer\DefaultRenderer;
use App\Service\Renderer\HtmlRenderer;
use App\Service\Renderer\JsonRenderer;
use App\Service\Renderer\Renderer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class RendererTest extends WebTestCase
{
    public function testRendererReturnsJsonRenderer(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'application/json');

        $renderer = Renderer::makeRenderer($request, [
            'json' => JsonRenderer::class,
        ]);

        self::assertInstanceOf(JsonRenderer::class, $renderer);
    }

    public function testRendererReturnsHtmlRenderer(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'text/html');

        $renderer = Renderer::makeRenderer($request, [
            'html' => HtmlRenderer::class,
        ]);

        self::assertInstanceOf(HtmlRenderer::class, $renderer);
    }

    public function testRendererReturnsDefaultRenderer(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'text/html');

        $renderer = Renderer::makeRenderer($request, []);
        self::assertInstanceOf(DefaultRenderer::class, $renderer);
    }
}
