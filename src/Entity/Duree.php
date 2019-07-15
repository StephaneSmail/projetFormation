<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DureeRepository")
 */
class Duree
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
    private $nbJour;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Atelier")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dureeAtelier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dureeFormation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbJour(): ?int
    {
        return $this->nbJour;
    }

    public function setNbJour(int $nbJour): self
    {
        $this->nbJour = $nbJour;

        return $this;
    }

    public function getDureeAtelier(): ?Atelier
    {
        return $this->dureeAtelier;
    }

    public function setDureeAtelier(?Atelier $dureeAtelier): self
    {
        $this->dureeAtelier = $dureeAtelier;

        return $this;
    }

    public function getDureeFormation(): ?Formation
    {
        return $this->dureeFormation;
    }

    public function setDureeFormation(?Formation $dureeFormation): self
    {
        $this->dureeFormation = $dureeFormation;

        return $this;
    }
}
