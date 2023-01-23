<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName('Electricity Simple');
        $product->setValidFrom(new DateTime('2021-01-01'));
        $product->setValidUntil(new DateTime('2023-12-31'));
        $this->addReference('tariff.product', $product);
        $manager->persist($product);

        $manager->flush();
    }
}
