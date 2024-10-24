<?php

namespace App\Repository;

use App\Entity\Devis;
use App\Entity\Filtres\DevisFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Devis>
 */
class DevisRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator,
        )
    {
        parent::__construct($registry, Devis::class);
    }

    public function findDevisData(DevisFilter $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('d')
            ->select('d');

        if (!empty($search->getQuery())) {
            $query = $query->andWhere('d.firstname LIKE :query OR d.lastname LIKE :query')
                ->setParameter('query', "%{$search->getQuery()}%");
        }

        if (!empty($search->getStatus())) {
            $query = $query->andWhere('d.status =:status')
                ->setParameter('status', $search->getStatus());
        }

        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            $search->getLimit(),
        );
    }

//    /**
//     * @return Devis[] Returns an array of Devis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Devis
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}