<?php

namespace App\Entity;

use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Session\Session;

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Session", mappedBy="salle", orphanRemoval=true)
     */
    private $sessions;

    /**
     * @ORM\Column(type="integer")
      
     */
    private $nbplaces;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Posseder", mappedBy="salles" , cascade={"persist"})
     */
    private $posseders;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->posseders = new ArrayCollection();
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

    public function getNbplaces(): ?int
    {
        return $this->nbplaces;
    }

    public function setNbplaces(int $nbplaces): self
    {
        $this->nbplaces = $nbplaces;

        return $this;
    }

    /**
     * @return Collection|Session[]
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setSalle($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->sessions->contains($session)) {
            $this->sessions->removeElement($session);
            // set the owning side to null (unless already changed)
            if ($session->getSalle() === $this) {
                $session->setSalle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Posseder[]
     */
    public function getPosseders(): Collection
    {
        return $this->posseders;
    }

    public function addPosseder(Posseder $posseder): self
    {
        if (!$this->posseders->contains($posseder)) {
            $this->posseders[] = $posseder;
            $posseder->setSalles($this);
        }

        return $this;
    }

    public function removePosseder(Posseder $posseder): self
    {
        if ($this->posseders->contains($posseder)) {
            $this->posseders->removeElement($posseder);
            // set the owning side to null (unless already changed)
            if ($posseder->getSalles() === $this) {
                $posseder->setSalles(null);
            }
        }

        return $this;
    }

    public function __ToString(){
        return 
        $this->getNbplaces(). ' place(s),';
        
    }

  
}
