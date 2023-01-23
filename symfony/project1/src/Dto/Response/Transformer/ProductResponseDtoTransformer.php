<?php

declare(strict_types=1);

namespace App\Dto\Response\Transformer;

use App\Dto\Response\ProductResponseDto;
use Exception;

class ProductResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @param array $object
     * @return ProductResponseDto
     * @throws Exception
     */
    public function transformFromObject($object): ProductResponseDto
    {
        extract($object, EXTR_OVERWRITE);

        $dto = new ProductResponseDto();
        $dto->productName = $product->getName();
        $dto->workingPriceNet = $tariff->getWorkingPriceNet();
        $dto->basePriceNet = $tariff->getBasePriceNet();
        $dto->downPayment = $monthlyPayments;

        return $dto;
    }
}