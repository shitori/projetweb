<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DisponibiliteRepository")
 */
class Disponibilite
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $jour;

    /**
     * @ORM\Column(type="time")
     */
    private $debut;

    /**
     * @ORM\Column(type="time")
     */
    private $fin;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Professeur", inversedBy="disponibilites")
     */
    private $prof_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    public function __construct()
    {
        $this->prof_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJour(): ?int
    {
        return $this->jour;
    }

    public function setJour(int $jour): self
    {
        $this->jour = $jour;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * @return Collection|professeur[]
     */
    public function getProfId(): Collection
    {
        return $this->prof_id;
    }

    public function addProfId(professeur $profId): self
    {
        if (!$this->prof_id->contains($profId)) {
            $this->prof_id[] = $profId;
        }

        return $this;
    }

    public function removeProfId(professeur $profId): self
    {
        if ($this->prof_id->contains($profId)) {
            $this->prof_id->removeElement($profId);
        }

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
