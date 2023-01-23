<?php

namespace Tests\Unit\Service\Renderer;

use App\Service\Renderer\JsonRenderer;
use App\Service\Renderer\RendererInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonRendererTest extends WebTestCase
{
    public function testJsonRendererImplementsRendererInterface(): void
    {
        $interfaces = class_implements(JsonRenderer::class);

        self::assertArrayHasKey(RendererInterface::class, $interfaces);
    }

    public function testItReturnsJsonResponse(): void
    {
        $renderer = new JsonRenderer();

        $data = [
            'a' => 1,
            'b' => 2,
        ];

        $response = $renderer->render($data);
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertJson(json_encode($data), $response->getContent());
    }

    public function testErrorResponseWith500(): void
    {
        $renderer = new JsonRenderer();

        $response = $renderer->error('error message');
        self::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testErrorResponseWithSelectedStatusCode(): void
    {
        $renderer = new JsonRenderer();

        $response = $renderer->error('error message', Response::HTTP_NOT_FOUND);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testErrorResponseWithMessage(): void
    {
        $renderer = new JsonRenderer();

        $response = $renderer->error('error message');
        self::assertJson(
            json_encode(['type' => 'error', 'message' => 'error message']),
            $response->getContent()
        );
    }
}
