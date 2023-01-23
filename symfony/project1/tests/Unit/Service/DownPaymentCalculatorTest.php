<?php

namespace Tests\Unit\Service;

use App\Entity\Bonus;
use App\Entity\Tariff;
use App\Service\DownPaymentCalculator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DownPaymentCalculatorTest extends WebTestCase
{
    protected DownPaymentCalculator $calculator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->calculator = self::getContainer()->get(DownPaymentCalculator::class);
        parent::setUp();
    }

    public function testWorkingPriceNetYearly(): void
    {
        $tariff = new Tariff();
        $tariff->setWorkingPriceNet(10);

        self::assertEquals(0, $this->calculator->getWorkingPriceNetYearly($tariff, 0));
        self::assertEquals(100, $this->calculator->getWorkingPriceNetYearly($tariff, 10));
    }

    public function testMonthlyDownPayment(): void
    {
        $tariff = new Tariff();
        $tariff->setBasePriceNet(120);
        $tariff->setWorkingPriceNet(5);

        self::assertEquals(10, $this->calculator->getMonthlyDownPayment($tariff, 0, 12));
        self::assertEquals(62, $this->calculator->getMonthlyDownPayment($tariff, 100, 10));
    }

    public function testMonthlyPaymentsWithoutBonuses(): void
    {
        $tariff = new Tariff();
        $tariff->setBasePriceNet(50);
        $tariff->setWorkingPriceNet(0.2);

        self::assertEquals([
            1 => 6.94,
            2 => 6.94,
            3 => 6.94,
            4 => 6.94,
            5 => 6.94,
            6 => 6.94,
            7 => 6.94,
            8 => 6.94,
            9 => 6.94,
            10 => 6.94,
            11 => 6.94,
            12 => 6.94
        ], $this->calculator->getMonthlyPayments($tariff, 100, 12, 19, []));
    }

    public function testMonthlyPaymentsWithBonuses(): void
    {
        $tariff = new Tariff();
        $tariff->setBasePriceNet(50);
        $tariff->setWorkingPriceNet(0.2);

        $bonus1 = new Bonus();
        $bonus1->setValue(5);
        $bonus1->setPaymentAfterMonths(0);

        $bonus2 = new Bonus();
        $bonus2->setValue(5);
        $bonus2->setPaymentAfterMonths(6);

        $calculator = $this->createMock(DownPaymentCalculator::class);

        self::assertEquals([
            1 => 6.59,
            2 => 6.59,
            3 => 6.59,
            4 => 6.59,
            5 => 6.59,
            6 => 6.59,
            7 => 6.26,
            8 => 6.26,
            9 => 6.26,
            10 => 6.26,
            11 => 6.26,
            12 => 6.26
        ], $this->calculator->getMonthlyPayments($tariff, 100, 12, 19, [$bonus1, $bonus2]));
    }
}
