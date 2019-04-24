<?php

namespace App\Repository;

use App\Entity\Agenda;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Exception;
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

    public function userAgenda($idUser)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'select *,agenda.id as idAgenda from agenda
                join professeur p on agenda.prof_id = p.id
                join user u on p.user_id = u.id
                where agenda.user_id = ?';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->bindValue(1, $idUser);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function profAgenda($idProf)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'select *,agenda.id as idAgenda from agenda
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

    public function noPlace($date, $hour, $idProf)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'select * from agenda
                where prof_id = ? and 
                datep = CONVERT(?, date ) 
                and debut = CONVERT(?,time )';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->bindValue(1, $idProf);
        $stmt->bindValue(2, $date);
        $stmt->bindValue(3, $hour);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function removeAgenda($idAgenda, $idCurrentUser)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'delete from agenda where id = ? and user_id = ?';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->bindValue(1, $idAgenda);
        $stmt->bindValue(2, $idCurrentUser);
        $stmt->execute();
        return "Le rendez vous a bien été surprimer";
    }


}
