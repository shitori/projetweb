<?php

namespace App\Repository;

use App\Entity\Competence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Competence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competence[]    findAll()
 * @method Competence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Competence::class);
    }

    public function profCompetence($idProf)
    {

        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'select * from competence 
                join competence_professeur cp on competence.id = cp.competence_id
                join professeur p on cp.professeur_id = p.id
                where p.id = ?';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->bindValue(1, $idProf);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insertCompetences($nameCompt, $nivCompt, $idProf)
    {
        $compt = $this->findOneBy(["matiere" => $nameCompt, "niveau" => $nivCompt]);
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'insert into competence_professeur value (?,?)';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->bindValue(1, $compt->getId());
        $stmt->bindValue(2, $idProf);
        $stmt->execute();
        return "La compétence a bien été ajouté";
    }

    // /**
    //  * @return Competence[] Returns an array of Competence objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Competence
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
