<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity("mail", message="Ce mail est déjà utilisé !")
 */
class Participant implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idParticipant;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motPasse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class)
     * @ORM\JoinColumn(nullable=false, name="id_campus", referencedColumnName="id_campus")
     */
    private $campus;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class)
     * 	@ORM\JoinTable(name = "participant_sortie",
     *      joinColumns = { @ORM\JoinColumn(name = "id_participant", referencedColumnName="id_participant") },
     *      inverseJoinColumns = { @ORM\JoinColumn(name = "id_sortie", referencedColumnName="id_sortie") })
     */
    private $sorties;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $apiToken;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function getIdParticipant(): ?int
    {
        return $this->idParticipant;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMotPasse(): ?string
    {
        return $this->motPasse;
    }

    public function setMotPasse(string $motPasse): self
    {
        $this->motPasse = $motPasse;

        return $this;
    }

    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus= $campus;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorties(Sortie $sortie): self
    {
        if (!$this->sorties->contains($sortie)) {
            $this->sorties[] = $sortie;
            $sortie->addParticipants($this);
        }

        return $this;
    }

    public function removeSorties(Sortie $sortie): self
    {
        if ($this->sorties->contains($sortie)) {
            $this->sorties->removeElement($sortie);
            $sortie->removeParticipants($this);
        }

        return $this;
    }

    public function getUsername()
    {
        return $this->getMail();
    }

    public function getPassword(): ?string
    {
        return $this->motPasse;
    }

    public function getRoles()
    {
        if ($this->getAdministrateur())
        {
            return ['ROLE_ADMIN','ROLE_USER'];
        }
        else {
            return ['ROLE_USER'];
        }
    }

    // Gérés par Symfony
    public function getSalt() { return null; }
    public function eraseCredentials() {}

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }
}
