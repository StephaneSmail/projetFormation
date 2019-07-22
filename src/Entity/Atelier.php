<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AtelierRepository")
 */
class Atelier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $nomAtelier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $programmer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Duree", mappedBy="ateliers", orphanRemoval=true)
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

    public function getNomAtelier(): ?string
    {
        return $this->nomAtelier;
    }

    public function setNomAtelier(string $nomAtelier): self
    {
        $this->nomAtelier = $nomAtelier;

        return $this;
    }

    public function getProgrammer(): ?Categorie
    {
        return $this->programmer;
    }

    public function setProgrammer(?Categorie $programmer): self
    {
        $this->programmer = $programmer;

        return $this;
    }

    /**
     * @return Collection|Duree[]
     */
    public function getDuree(): Collection
    {
        return $this->duree;
    }

    public function addDuree(Duree $duree): self
    {
        if (!$this->duree->contains($duree)) {
            $this->duree[] = $duree;
            $duree->setAteliers($this);
        }

        return $this;
    }

    public function removeDuree(Duree $duree): self
    {
        if ($this->duree->contains($duree)) {
            $this->duree->removeElement($duree);
            // set the owning side to null (unless already changed)
            if ($duree->getAteliers() === $this) {
                $duree->setAteliers(null);
            }
        }

        return $this;
    }

    public function __ToString(){
        return 
        $this->getNomAtelier();


}
}
