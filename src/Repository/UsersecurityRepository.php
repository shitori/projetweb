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
}
