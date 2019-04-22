<?php

namespace App\Repository;

use App\Entity\Agenda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Agenda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agenda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agenda[]    findAll()
 * @method Agenda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgendaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Agenda::class);
    }

    public function userAgenda($idUser){
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'select * from agenda
                join professeur p on agenda.prof_id = p.id
                join user u on agenda.user_id = u.id
                where u.id = ?';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->bindValue(1, $idUser);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function profAgenda($idProf){
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'select * from agenda
                join professeur p on agenda.prof_id = p.id
                join user u on agenda.user_id = u.id
                where p.id = ?';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->bindValue(1, $idProf);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // /**
    //  * @return Agenda[] Returns an array of Agenda objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Agenda
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
