<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PossederRepository")
 */
class Posseder
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
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Salle", inversedBy="posseders", cascade={"persist"})
     */
    private $salles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Materiel", inversedBy="posseders", cascade={"persist"})
     */
    private $materiels;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getSalles(): ?Salle
    {
        return $this->salles;
    }

    public function setSalles(?Salle $salles): self
    {
        $this->salles = $salles;

        return $this;
    }

    public function getMateriels(): ?Materiel
    {
        return $this->materiels;
    }

    public function setMateriels(?Materiel $materiels): self
    {
        $this->materiels = $materiels;

        return $this;
    }

    public function __ToString(){
        return 
        $this->getMateriels().': '.$this->getQuantite(). ' jour(s),';
        
    }
}

