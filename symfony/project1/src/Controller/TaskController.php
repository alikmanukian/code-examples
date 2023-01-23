<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Response\Transformer\ProductResponseDtoTransformer;
use App\Repository\BonusRepository;
use App\Repository\ProductRepository;
use App\Repository\TariffRepository;
use App\Request\CalculateRequest;
use App\Service\DownPaymentCalculator;
use App\Service\Renderer\HtmlRenderer;
use App\Service\Renderer\JsonRenderer;
use App\Service\Renderer\Renderer;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskController extends AbstractController
{
    #[Route('/calculate', name: 'calculate')]
    public function calculate(
        Request $request,
        ValidatorInterface $validator,
        ProductResponseDtoTransformer $productResponseDtoTransformer,
        ProductRepository $productRepository,
        TariffRepository $tariffRepository,
        BonusRepository $bonusRepository,
    ): JsonResponse|Response {

        $renderer = Renderer::makeRenderer($request, [
            'json' => JsonRenderer::class,
            'html' => HtmlRenderer::class,
        ]);

        $errors = $validator->validate(
            new CalculateRequest(
                $request->get('vat'),
                $request->get('down_payment_interval'),
                $request->get('yearly_usage')
            )
        );

        if (count($errors) > 0) {
            return $renderer->error($errors[0]->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = (new DownPaymentCalculator(
                $productResponseDtoTransformer,
                $productRepository,
                $tariffRepository,
                $bonusRepository
            ))->calculate($request);

            return $renderer->render($data->toArray());
        } catch (Exception $e) {
            return $renderer->error($e->getMessage(), $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
