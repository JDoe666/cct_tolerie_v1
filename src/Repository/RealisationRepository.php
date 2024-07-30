<?php

namespace App\Repository;

use App\Entity\Filtres\RealisationFilter;
use App\Entity\Realisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Realisation>
 */
class RealisationRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator,
        )
    {
        parent::__construct($registry, Realisation::class);
    }

    public function findRealisationData(RealisationFilter $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('r')
            ->select('r');

        if (!empty($search->getQuery())) {
            $query = $query->andWhere('r.name LIKE :query OR r.description LIKE :query OR r.categories LIKE :query')
                ->setParameter('query', "%{$search->getQuery()}%");
        }

        if (!empty($search->getCategories())) {
            $query = $query->andWhere('r.categories =:categories')
                ->setParameter('categories', true);
        }

        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            $search->getLimit(),
        );
    }

    //    /**
    //     * @return Realisation[] Returns an array of Realisation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Realisation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}