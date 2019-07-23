<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalleRepository")
 */
class Salle
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
    private $nomSalle;

<<<<<<< HEAD

=======
    
>>>>>>> 5006c232663d2c208b2a5f39deb6af68931672fb
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Session", mappedBy="salle", orphanRemoval=true)
     */
    private $meubler;
<<<<<<< HEAD
=======

    /**
     * @ORM\Column(type="integer")
     */
    private $nbplaces;
>>>>>>> 5006c232663d2c208b2a5f39deb6af68931672fb

    public function __construct()
    {
        $this->meubler = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSalle(): ?string
    {
        return $this->nomSalle;
    }

    public function setNomSalle(string $nomSalle): self
    {
        $this->nomSalle = $nomSalle;

        return $this;
    }


    /**
     * @return Collection|Session[]
     */
    public function getMeubler(): Collection
    {
        return $this->meubler;
    }

    public function addMeubler(Session $meubler): self
    {
        if (!$this->meubler->contains($meubler)) {
            $this->meubler[] = $meubler;
            $meubler->setSalle($this);
        }

        return $this;
    }

    public function removeMeubler(Session $meubler): self
    {
        if ($this->meubler->contains($meubler)) {
            $this->meubler->removeElement($meubler);
            // set the owning side to null (unless already changed)
            if ($meubler->getSalle() === $this) {
                $meubler->setSalle(null);
            }
        }

        return $this;
    }

    public function getNbplaces(): ?int
    {
        return $this->nbplaces;
    }

    public function setNbplaces(int $nbplaces): self
    {
        $this->nbplaces = $nbplaces;

        return $this;
    }
}
