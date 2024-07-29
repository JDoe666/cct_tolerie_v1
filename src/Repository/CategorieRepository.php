<?php

namespace App\Repository;

use App\Entity\Categorie;
use App\Entity\Filtres\CategorieFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator,)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function findCategorieData(CategorieFilter $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('c')
            ->select('c');

        if (!empty($search->getQuery())) {
            $query = $query->andWhere('c.name LIKE :query')
                ->setParameter('query', "%{$search->getQuery()}%");
        }

        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            $search->getLimit(),
        );
    }
//    /**
//     * @return Categorie[] Returns an array of Categorie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Categorie
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}