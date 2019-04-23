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
        $tabTest = $this->profCompetence($idProf);
        foreach ($tabTest as $value){
            if ($value["competence_id"] == $compt->getId()){
                return "La compétence a déja été ajouté";
            }
        }
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

    public function removeCompetence($idCompt,$idProf){
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'delete from competence_professeur 
                where competence_id= ? and professeur_id = ?';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->bindValue(1, $idCompt);
        $stmt->bindValue(2, $idProf);
        $stmt->execute();
        return "La compétence a bien été supprimé";
    }


}
