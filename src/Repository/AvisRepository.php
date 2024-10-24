<?php

namespace App\Repository;

use App\Entity\Avis;
use App\Entity\Filtres\AvisFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Avis>
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator,
    ) {
        parent::__construct($registry, Avis::class);
    }

    public function findThreeRandomAvis(): Array {
       return $this->createQueryBuilder('a')
        ->andWhere('a.note >= 3')->orderBy('RAND()')
        ->setMaxResults(3)->getQuery()->getResult();
    }
    //    /**
    //     * @return Avis[] Returns an array of Avis objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Avis
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}