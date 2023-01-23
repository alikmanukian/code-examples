<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Tariff;
use ArrayIterator;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Tariff>
 */
class TariffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getTariff(Product $product, int $yearlyUsage = 0): ?Tariff
    {
        $now = new DateTime('now');

        $collection = $product
            ->getTariffs()
            ->filter(function ($tariff) use ($now, $yearlyUsage) {
                return $tariff->getValidFrom() <= $now
                    && $tariff->getValidUntil() >= $now
                    && $tariff->getUsageFrom() <= $yearlyUsage;
            });

        /**
         * Couldn't find solution out of box in symfony ArrayCollection
         * to sort collection (usage_from desc), so I did custom sort
         *
         * Without sorting for find correct result I had to iterate through
         * whole array to find max possible result
         */
        try {
            /** @var ArrayIterator $iterator */
            $iterator = $collection->getIterator();
        } catch (Exception $e) {
            return null;
        }

        $iterator->uasort(function (Tariff $a, Tariff $b) {
            return $b->getUsageFrom() <=> $a->getUsageFrom();
        });

        return $iterator->current();
    }
}
