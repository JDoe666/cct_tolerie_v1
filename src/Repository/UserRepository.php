<?php

namespace App\Repository;

use App\Entity\Filtres\UserFilter;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findUserData(UserFilter $search): array
    {
        $query = $this->createQueryBuilder('u')
            ->select('u');

        if (!empty($search->getQuery())) {
            $query = $query->andWhere('u.firstname LIKE :query OR u.lastname LIKE :query OR u.email LIKE :query')
                ->setParameter('query', "%{$search->getQuery()}%");
        }
        
        if (!empty($search->getIsVerified())) {
            $query = $query->andWhere('u.isVerified =:isVerified')
                           ->setParameter('isVerified', true);
        }
        
        if (!empty($search->getRoles())) {
            if ($search->getRoles() === 'ROLE_USER') {
                $query = $query->andWhere('u.roles LIKE :roles')
                    ->setParameter('roles', "[]");
            } else {
                $query = $query->andWhere('u.roles LIKE :roles')
                    ->setParameter('roles', "%{$search->getRoles()}%");
            }
        }

        return $query->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}