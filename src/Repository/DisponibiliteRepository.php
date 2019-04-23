<?php

namespace App\Repository;

use App\Entity\Disponibilite;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Disponibilite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Disponibilite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Disponibilite[]    findAll()
 * @method Disponibilite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisponibiliteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Disponibilite::class);
    }

    public function profDispo($idProf)
    {

        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'select * from disponibilite 
                join disponibilite_professeur dp on disponibilite.id = dp.disponibilite_id
                join professeur p on dp.professeur_id = p.id
                where p.id = ?';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->bindValue(1, $idProf);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insertDispo($dayDispo, $hourDispoS, $idProf)
    {
        $dispo = $this->findOneBy(["jour" => $dayDispo, "debut" => new DateTime($hourDispoS)]);
        $tabTest = $this->profDispo($idProf);
        foreach ($tabTest as $value) {
            if ($value["disponibilite_id"] == $dispo->getId()) {
                return "La compétence a déja été ajouté";
            }
        }
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'insert into disponibilite_professeur value (?,?)';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->bindValue(1, $dispo->getId());
        $stmt->bindValue(2, $idProf);
        $stmt->execute();
        return "La compétence a bien été ajouté";
    }

    public function removeDispo($idDispo, $idProf)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'delete from disponibilite_professeur 
                where disponibilite_id= ? and professeur_id = ?';
        try {
            $stmt = $conn->prepare($sql);
        } catch (DBALException $e) {
        }
        $stmt->bindValue(1, $idDispo);
        $stmt->bindValue(2, $idProf);
        $stmt->execute();
        return "La compétence a bien été supprimé";
    }

}
