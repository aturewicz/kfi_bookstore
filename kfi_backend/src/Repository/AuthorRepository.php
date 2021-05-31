<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function findAuthors(string $search)
    {
        return $this->createQueryBuilder('a')
            ->select('a.id', 'a.fullName as name')
            ->andWhere('a.fullName LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery()
            ->getResult();
    }

    public function findAllAuthors()
    {
        $qb = $this->createQueryBuilder('a');

        return $qb
            ->select('a.id', 'a.fullName as fullName', 'COUNT(p) as products', 'AVG(p.price) as avgProductsPrice')
            ->innerJoin('a.products', 'p')
            ->addGroupBy('a.id')
            ->having('products >= 0')
            ->getQuery()
            ->getResult();
    }
}
