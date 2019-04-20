<?php

namespace App\Repository;

use App\Entity\Professeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Professeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Professeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Professeur[]    findAll()
 * @method Professeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfesseurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Professeur::class);
    }


    public function allProf()
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'select * from professeur
    join user u on professeur.user_id = u.id';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function allProfFilter($matiere, $ville)
    {
        $conn = $this->getEntityManager()->getConnection();
        if ($matiere == "" && $ville == "") {
            $sql = 'select * from professeur
                join user u on professeur.user_id = u.id
                join competence_professeur cp on professeur.id = cp.professeur_id
                join competence c on cp.competence_id = c.id';
            try {
                $stmt = $conn->prepare($sql);
            } catch (DBALException $e) {
            }
        } elseif ($matiere == "") {
            $sql = 'select * from professeur
                join user u on professeur.user_id = u.id
                join competence_professeur cp on professeur.id = cp.professeur_id
                join competence c on cp.competence_id = c.id
                where u.ville = ?';
            try {
                $stmt = $conn->prepare($sql);
            } catch (DBALException $e) {
            }
            $stmt->bindValue(1, $ville);
        } elseif ($ville == ""){
            $sql = 'select * from professeur
                join user u on professeur.user_id = u.id
                join competence_professeur cp on professeur.id = cp.professeur_id
                join competence c on cp.competence_id = c.id
                where c.matiere = ? ';
            try {
                $stmt = $conn->prepare($sql);
            } catch (DBALException $e) {
            }
            $stmt->bindValue(1, $matiere);
        } else{
            $sql = 'select * from professeur
                join user u on professeur.user_id = u.id
                join competence_professeur cp on professeur.id = cp.professeur_id
                join competence c on cp.competence_id = c.id
                where u.ville = ? and c.matiere = ? ';
            try {
                $stmt = $conn->prepare($sql);
            } catch (DBALException $e) {
            }
            $stmt->bindValue(1, $ville);
            $stmt->bindValue(2, $matiere);
        }


        $stmt->execute();
        return $stmt->fetchAll();
    }

    // /**
    //  * @return Professeur[] Returns an array of Professeur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Professeur
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
