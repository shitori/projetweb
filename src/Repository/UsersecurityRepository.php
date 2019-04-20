<?php

namespace App\Repository;

use App\Entity\Usersecurity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Usersecurity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usersecurity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usersecurity[]    findAll()
 * @method Usersecurity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersecurityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Usersecurity::class);
    }

    // /**
    //  * @return Usersecurity[] Returns an array of Usersecurity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Usersecurity
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
