<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Tariff;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class TariffFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addTariff($manager, [
            'name' => 'Tariff 1',
            'usageFrom' => 0,
            'validFrom' => '2021-01-01',
            'validUntil' => '2022-01-01',
            'workingPriceNet' => 0.2,
            'basePriceNet' => 50
        ]);

        $this->addTariff($manager, [
            'name' => 'Tariff 2',
            'usageFrom' => 0,
            'validFrom' => '2022-01-01',
            'validUntil' => '2023-12-31',
            'workingPriceNet' => 0.2,
            'basePriceNet' => 50
        ]);

        $this->addTariff($manager, [
            'name' => 'Tariff 3',
            'usageFrom' => 3001,
            'validFrom' => '2022-01-01',
            'validUntil' => '2023-12-31',
            'workingPriceNet' => 0.15,
            'basePriceNet' => 40
        ]);

        $this->addTariff($manager, [
            'name' => 'Tariff 3',
            'usageFrom' => 5001,
            'validFrom' => '2022-01-01',
            'validUntil' => '2023-12-31',
            'workingPriceNet' => 0.12,
            'basePriceNet' => 35
        ]);

        $manager->flush();
    }

    /**
     * @throws Exception
     */
    private function addTariff($manager, $data)
    {
        extract($data);

        $tariff = new Tariff();
        $tariff->setName($name);
        $tariff->setProduct($this->getReference('tariff.product'));
        $tariff->setUsageFrom($usageFrom);
        $tariff->setValidFrom(new DateTime($validFrom));
        $tariff->setValidUntil(new DateTime($validUntil));
        $tariff->setWorkingPriceNet($workingPriceNet);
        $tariff->setBasePriceNet($basePriceNet);
        $manager->persist($tariff);
    }
}
