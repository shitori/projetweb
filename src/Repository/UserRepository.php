<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function modif($ln,$fn,$sexe,$city,$phone,$bd,$idUser){
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = "update user
                    set nom=?,
                    prenom=?,
                    ville=?,
                    sexe=?,
                    phone=?,
                    birthday=convert(?,DATE )
                    where id = ?";
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->bindValue(1, $ln);
        $stmt->bindValue(2, $fn);
        $stmt->bindValue(3, $city);
        $stmt->bindValue(4, $sexe);
        $stmt->bindValue(5, $phone);
        $stmt->bindValue(6, $bd->format('Y-m-d'));
        $stmt->bindValue(7, $idUser);
        $stmt->execute();
        return "Profil modifié";
    }

}
