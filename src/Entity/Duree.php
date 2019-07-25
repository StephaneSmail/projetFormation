<?php

namespace App\Entity;

use App\Entity\Duree;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Formation", inversedBy="duree", cascade={"persist"})
     */
    private $formations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Atelier", inversedBy="duree", cascade={"persist"})
     */
    private $ateliers;

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

    

    public function getFormations(): ?Formation
    {
        return $this->formations;
    }

    public function setFormations(?Formation $formations): self
    {
        $this->formations = $formations;

        return $this;
    }

    public function getAteliers(): ?Atelier
    {
        return $this->ateliers;
    }

    public function setAteliers(?Atelier $ateliers): self
    {
        $this->ateliers = $ateliers;

        return $this;
    }

    public function __ToString(){
        return 
        $this->getAteliers().': '.$this->getNbJour(). ' jour(s),';
        
    }
}
