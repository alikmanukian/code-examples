<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Response\ProductResponseDto;
use App\Dto\Response\Transformer\ProductResponseDtoTransformer;
use App\Entity\Tariff;
use App\Repository\BonusRepository;
use App\Repository\ProductRepository;
use App\Repository\TariffRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DownPaymentCalculator
{
    public function __construct(
        private ProductResponseDtoTransformer $productResponseDtoTransformer,
        private ProductRepository $productRepository,
        private TariffRepository $tariffRepository,
        private BonusRepository $bonusRepository
    ) {
    }

    /**
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function calculate(Request $request): ProductResponseDto
    {
        if (!$product = $this->productRepository->getProduct()) {
            throw new RuntimeException("Product not found", Response::HTTP_NOT_FOUND);
        }

        $yearlyUsage = (int)$request->query->get('yearly_usage');
        $vat = (float)$request->query->get('vat');
        $downPaymentInterval = (int)$request->query->get('down_payment_interval');

        $tariff = $this->tariffRepository->getTariff($product, $yearlyUsage);

        if (!$tariff) {
            throw new RuntimeException('No tariff selected', Response::HTTP_NOT_FOUND);
        }

        $bonuses = $this->bonusRepository->getBonuses($yearlyUsage);
        $monthlyPayments = $this->getMonthlyPayments($tariff, $yearlyUsage, $downPaymentInterval, $vat, $bonuses);

        return $this->productResponseDtoTransformer->transformFromObject(
            compact('product', 'tariff', 'monthlyPayments')
        );
    }

    public function getMonthlyPayments(
        Tariff $tariff,
        int $yearlyUsage,
        int $downPaymentInterval,
        float $vat,
        array $bonuses
    ): array {
        $payments = [];
        for ($i = 1; $i <= $downPaymentInterval; $i++) {
            $monthlyDownPayment = $this->getMonthlyDownPayment($tariff, $yearlyUsage, $downPaymentInterval);
            $monthlyDownPayment = $this->applyBonuses($bonuses, $i, $monthlyDownPayment);
            $payments[$i] = round($monthlyDownPayment + ($monthlyDownPayment * $vat / 100), 2);
        }

        return $payments;
    }

    public function getWorkingPriceNetYearly(Tariff $tariff, int $yearlyUsage, $debug = false): float
    {
        return $tariff->getWorkingPriceNet() * $yearlyUsage;
    }

    public function getMonthlyDownPayment(Tariff $tariff, int $yearlyUsage, int $downPaymentInterval): float
    {
        return ($tariff->getBasePriceNet() + $this->getWorkingPriceNetYearly(
                    $tariff,
                    $yearlyUsage
                )) / $downPaymentInterval;
    }

    private function applyBonuses(array $bonuses, int $month, float &$monthlyDownPayment): float
    {
        foreach ($bonuses as $bonus) {
            if ($month > $bonus->getPaymentAfterMonths()) {
                // add here the bonus on the staring monthly down payment, not the resulted
                $monthlyDownPayment -= ($monthlyDownPayment * ((float)($bonus->getValue() / 100)));
            }
        }

        return $monthlyDownPayment;
    }
}