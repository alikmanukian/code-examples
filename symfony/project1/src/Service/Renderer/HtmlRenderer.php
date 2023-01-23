<?php

declare(strict_types=1);

namespace App\Service\Renderer;

use Symfony\Component\HttpFoundation\Response;

class HtmlRenderer implements RendererInterface
{
    public function render(mixed $data): Response
    {
        return new Response($this->getContent($data));
    }

    public function error(string $message, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): Response
    {
        return new Response($message, $code);
    }

    private function getContent(array $data): string
    {
        extract($data);

        $html = "
            <html lang=\"en\">
            <div>
                <p>Product Name: $productName</p>
                <p>Tariff Base Price Net: $basePriceNet EUR</p>
                <p>Tariff Working Price Net: $workingPriceNet Cent</p>
            </div>
            <div>
            ";

        foreach($downPayment as $month => $payment) {
            $html .= "<p>Monthly down payment: $month - $payment EUR</p>";
        }

        $html .= "</div></html>";

        return $html;
    }
}