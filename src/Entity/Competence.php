<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompetenceRepository")
 */
class Competence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matiere;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Professeur", inversedBy="competences")
     */
    private $prof;

    /**
     * @ORM\Column(type="integer")
     */
    private $niveau;

    public function __construct()
    {
        $this->prof = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * @return Collection|professeur[]
     */
    public function getProfId(): Collection
    {
        return $this->prof;
    }

    public function addProfId(Professeur $profId): self
    {
        if (!$this->prof->contains($profId)) {
            $this->prof[] = $profId;
        }

        return $this;
    }

    public function removeProfId(professeur $profId): self
    {
        if ($this->prof->contains($profId)) {
            $this->prof->removeElement($profId);
        }

        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(int $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }
}
