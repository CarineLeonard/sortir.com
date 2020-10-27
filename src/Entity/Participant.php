<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity("mail", message="Ce mail est déjà utilisé !")
 * @UniqueEntity("pseudo", message="Ce pseudo est déjà utilisé !")
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
     * @Assert\NotBlank(message="Le nom ne doit pas être vide !")
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Votre nom ne peut pas être supérieur à {{ limit }} charactères",
     *      allowEmptyString = false
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Le prenom ne doit pas être vide !")
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Votre prenom ne peut pas être supérieur à {{ limit }} charactères",
     *      allowEmptyString = false
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\Length(
     *      min = 10,
     *      max = 20,
     *      minMessage="Votre téléphone doit contenir au moins {{ limit }} charactères.",
     *      maxMessage = "Votre téléphone ne peut pas être supérieur à {{ limit }} charactères",
     *      allowEmptyString = false
     * )
     * @Assert\Regex (
     *     pattern="/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/",
     *     message="Votre téléphone doit être un numéro français valide, et commencer par +33 ou 0...")
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="Le mail ne doit pas être vide !")
     * @Assert\Email(message = "L'email '{{ value }}' n'est pas un email valide.")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Votre email ne peut pas être supérieur à {{ limit }} charactères",
     *      allowEmptyString = false
     * )
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le mot de passe ne doit pas être vide !")
     */
    private $motPasse = 'Passw0rd';

    /**
     * @Assert\Regex(
     *     pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/",
     *     match=true,
     *     message="Votre mot de passe doit contenir à minima 8 caractères, avec au moins une minuscule, une majuscule et un chiffre.")
     */
    private $newPassword;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="id_campus", referencedColumnName="id_campus")
     * @Assert\NotBlank(message="Le campus ne doit pas être vide !")
     */
    private $campus;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, cascade={"remove"})
     * 	@ORM\JoinTable(name = "participant_sortie",
     *      joinColumns = { @ORM\JoinColumn(name = "id_participant", referencedColumnName="id_participant") },
     *      inverseJoinColumns = { @ORM\JoinColumn(name = "id_sortie", referencedColumnName="id_sortie") })
     */
    private $sorties;

    /**
     * @ORM\Column(type="string", length=30 , unique=true)
     * @Assert\NotBlank(message="Le pseudo ne doit pas être vide !")
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Votre pseudo ne peut pas être supérieur à {{ limit }} charactères",
     * )
     */
    private $pseudo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $imageFilename;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function getIdParticipant(): ?int
    {
        return $this->idParticipant;
    }

    public function getId(): ?int
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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function __toString(){
        return $this->pseudo;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFilename()
    {
        return $this->imageFilename;
    }

    /**
     * @param mixed $imageFilename
     */
    public function setImageFilename($imageFilename)
    {
        $this->imageFilename = $imageFilename;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword): void
    {
        $this->newPassword = $newPassword;
    }



}
