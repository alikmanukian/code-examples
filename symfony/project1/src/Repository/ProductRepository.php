<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getProduct(): ?Product
    {
        $now = (new DateTime('now'))->format('Y-m-d');

        return $this->createQueryBuilder('p')
            ->andWhere('p.validFrom <= :now')
            ->andWhere('p.validUntil >= :now')
            ->setParameter('now', $now)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
