<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Bonus;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bonus>
 */
class BonusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bonus::class);
    }

    public function getBonuses(int $yearlyUsage = 0): array
    {
        $now = (new DateTime('now'))->format('Y-m-d');

        return $this->createQueryBuilder('b')
            ->andWhere('b.validFrom <= :now')
            ->andWhere('b.validUntil >= :now')
            ->andWhere('b.usageFrom <= :yearlyUsage')
            ->setParameter('now', $now)
            ->setParameter('yearlyUsage', $yearlyUsage)
            ->getQuery()
            ->getResult();
    }
}
