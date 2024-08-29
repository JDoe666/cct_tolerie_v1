<?php

namespace App\Repository;

use App\Entity\Devis;
use App\Entity\Logs\DevisLogs;
use App\Entity\Filtres\DevisLogsFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<DevisLogs>
 */
class DevisLogsRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator
    ) {
        parent::__construct($registry, DevisLogs::class);
    }

    public function findDevisLogsData(DevisLogsFilter $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('d')
            ->select('d');

        if (!empty($search->getActions())) {
            $query = $query->andWhere('d.action =:action')
                ->setParameter('action', $search->getActions());
        }

        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            $search->getLimit(),
        );
    }
    //    /**
    //     * @return DevisLogs[] Returns an array of DevisLogs objects
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

    //    public function findOneBySomeField($value): ?DevisLogs
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}