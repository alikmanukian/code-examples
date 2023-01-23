<?php

namespace Tests\Unit\Service\Renderer;

use App\Service\Renderer\DefaultRenderer;
use App\Service\Renderer\RendererInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultRendererTest extends WebTestCase
{
    public function testDefaultRendererImplementsRendererInterface(): void
    {
        $interfaces = class_implements(DefaultRenderer::class);

        self::assertArrayHasKey(RendererInterface::class, $interfaces);
    }

    public function testErrorResponseWith500(): void
    {
        $renderer = new DefaultRenderer();

        $response = $renderer->error('error message');
        self::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testErrorResponseWithSelectedStatusCode(): void
    {
        $renderer = new DefaultRenderer();

        $response = $renderer->error('error message', Response::HTTP_NOT_FOUND);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testErrorResponseWithMessage(): void
    {
        $renderer = new DefaultRenderer();

        $response = $renderer->error('error message');

        self::assertEquals('error message', $response->getContent());
    }
}
