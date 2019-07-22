<?php

namespace App\Entity;

use App\Entity\Duree;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormationRepository")
 */
class Formation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $nomFormation;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptif;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Duree", mappedBy="formations", orphanRemoval=true, cascade={"persist"})
     */
    private $duree;

    public function __construct()
    {
        $this->duree = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFormation(): ?string
    {
        return $this->nomFormation;
    }

    public function setNomFormation(string $nomFormation): self
    {
        $this->nomFormation = $nomFormation;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }
    /**
     * Get the value of duree
     */ 
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set the value of duree
     *
     * @return  self
     */ 
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    public function __ToString(){
        return $this->getNomFormation();
    }

    /**
     * @return Collection|Duree[]
     */
    

    public function addDuree(Duree $duree): self
    {
        if (!$this->duree->contains($duree)) {
            $this->duree[] = $duree;
            $duree->setFormations($this);
        }

        return $this;
    }

    public function removeDuree(Duree $duree): self
    {
        if ($this->duree->contains($duree)) {
            $this->duree->removeElement($duree);
            // set the owning side to null (unless already changed)
            if ($duree->getFormations() === $this) {
                $duree->setFormations(null);
            }
        }

        return $this;

   
    }
}



    
