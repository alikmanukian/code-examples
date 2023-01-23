<?php

namespace Tests\Unit\Service\Renderer;

use App\Service\Renderer\HtmlRenderer;
use App\Service\Renderer\RendererInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HtmlRendererTest extends WebTestCase
{
    public function testHtmlRendererImplementsRendererInterface(): void
    {
        $interfaces = class_implements(HtmlRenderer::class);

        self::assertArrayHasKey(RendererInterface::class, $interfaces);
    }

    public function testProductNameIsRequired(): void
    {
        $renderer = new HtmlRenderer();

        $this->expectErrorMessage('Undefined variable $productName');
        $this->expectError();

        $data = [];

        $renderer->render($data);
        self::assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testBasePriceNetIsRequired(): void
    {
        $renderer = new HtmlRenderer();

        $this->expectErrorMessage('Undefined variable $basePriceNet');
        $this->expectError();

        $data = [
            'productName' => 'test'
        ];

        $renderer->render($data);
        self::assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testWorkingPriceNetIsRequired(): void
    {
        $renderer = new HtmlRenderer();

        $this->expectErrorMessage('Undefined variable $workingPriceNet');
        $this->expectError();

        $data = [
            'productName' => 'test',
            'basePriceNet' => 10.0
        ];

        $renderer->render($data);
        self::assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testDownPaymentIsRequired(): void
    {
        $renderer = new HtmlRenderer();

        $this->expectErrorMessage('Undefined variable $downPayment');
        $this->expectError();

        $data = [
            'productName' => 'test',
            'basePriceNet' => 10.0,
            'workingPriceNet' => 10.0
        ];

        $renderer->render($data);
        self::assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testDownPaymentMustBeArrayPassed(): void
    {
        $renderer = new HtmlRenderer();

        $this->expectErrorMessage('foreach() argument must be of type array|object, string given');
        $this->expectError();

        $data = [
            'productName' => 'test',
            'basePriceNet' => 10.0,
            'workingPriceNet' => 10.0,
            'downPayment' => 'asd'
        ];

        $renderer->render($data);
        self::assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testAllVariablePassed(): void
    {
        $renderer = new HtmlRenderer();

        $data = [
            'productName' => 'test',
            'basePriceNet' => 10.0,
            'workingPriceNet' => 10.0,
            'downPayment' => []
        ];

        $response = $renderer->render($data);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testErrorResponseWith500(): void
    {
        $renderer = new HtmlRenderer();

        $response = $renderer->error('error message');
        self::assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testErrorResponseWithSelectedStatusCode(): void
    {
        $renderer = new HtmlRenderer();

        $response = $renderer->error('error message', Response::HTTP_NOT_FOUND);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testErrorResponseWithMessage(): void
    {
        $renderer = new HtmlRenderer();

        $response = $renderer->error('error message');

        self::assertEquals('error message', $response->getContent());
    }
}
