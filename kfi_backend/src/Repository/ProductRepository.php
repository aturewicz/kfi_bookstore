<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param array $params
     * @return int|mixed|string
     */
    public function findProducts(array $params)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.name', 'p.ean', 'p.stock', 'p.price');

        if (array_key_exists('author', $params)) {
            $qb->leftJoin('p.authors', 'a')
                ->andWhere('a.fullName = :author')
                ->setParameter('author', $params['author']);
        }

        if (array_key_exists('publisher', $params)) {
            $qb->leftJoin('p.publisher', 'publisher')
                ->andWhere('publisher.name = :publisher')
                ->setParameter('publisher', $params['publisher']);
        }

        return $qb->getQuery()->getResult();
    }
}
