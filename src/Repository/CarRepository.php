<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    private const FILTERS = [
        'brand'      => 'c.brand',
        'color'      => 'c.color',
        'category'   => 'c.category',
        'seatNumber' => 'c.seatNumber',
    ];

    public function findByFilters(array $criteria): Query
    {
        $queryBuilder = $this->createQueryBuilder('c');

        foreach (self::FILTERS as $key => $field) {
            $this->addFilter($queryBuilder, $criteria, $key, $field);
        }

        return $queryBuilder->getQuery();
    }

    private function addFilter(QueryBuilder $queryBuilder, array $criteria, string $key, string $field): void
    {
        if (!empty($criteria[$key])) {
            $queryBuilder
                ->andWhere("$field = :$key")
                ->setParameter($key, $criteria[$key])
            ;
        }
    }
}
