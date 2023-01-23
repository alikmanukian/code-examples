<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Bonus;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BonusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addBonus($manager, [
            'name' => 'BONUS-A',
            'usageFrom' => 0,
            'validFrom' => '2021-01-01',
            'validUntil' => '2023-12-31',
            'value' => 5,
            'paymentAfterMonths' => 0
        ]);

        $this->addBonus($manager, [
            'name' => 'BONUS-B',
            'usageFrom' => 0,
            'validFrom' => '2021-01-01',
            'validUntil' => '2023-12-31',
            'value' => 5,
            'paymentAfterMonths' => 6
        ]);

        $this->addBonus($manager, [
            'name' => 'BONUS-C',
            'usageFrom' => 2500,
            'validFrom' => '2021-01-01',
            'validUntil' => '2023-12-31',
            'value' => 2.5,
            'paymentAfterMonths' => 3
        ]);

        $this->addBonus($manager, [
            'name' => 'BONUS-D',
            'usageFrom' => 4500,
            'validFrom' => '2021-01-01',
            'validUntil' => '2023-12-31',
            'value' => 1.25,
            'paymentAfterMonths' => 9
        ]);

        $manager->flush();
    }

    /**
     * @throws \Exception
     */
    private function addBonus($manager, $data)
    {
        extract($data);

        $bonus = new Bonus();
        $bonus->setName($name);
        $bonus->setUsageFrom($usageFrom);
        $bonus->setValidFrom(new DateTime($validFrom));
        $bonus->setValidUntil(new DateTime($validUntil));
        $bonus->setValue($value);
        $bonus->setPaymentAfterMonths($paymentAfterMonths);
        $manager->persist($bonus);
    }
}
