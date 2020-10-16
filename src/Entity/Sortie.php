<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idSortie;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Le nom ne doit pas être vide !")
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de début ne doit pas être vide !")
     */
    private $dateHeureDebut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="La durée ne doit pas être vide !")
     */
    private $duree;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date limite d'inscription ne doit pas être vide !")
     */
    private $dateLimiteInscription;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le nombre de places ne doit pas être vide !")
     */
    private $nbInscriptionsMax;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Assert\NotBlank(message="La description ne doit pas être vide !")
     */
    private $infosSortie;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="id_participant", referencedColumnName="id_participant")
     */
    private $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="id_lieu", referencedColumnName="id_lieu")
     * @Assert\NotBlank(message="Le lieu ne doit pas être vide !")
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="id_etat", referencedColumnName="id_etat")
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="id_campus", referencedColumnName="id_campus")
     */
    private $siteOrganisateur;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, cascade={"remove"})
     * @ORM\JoinTable(name = "sortie_participant",
     *      joinColumns = { @ORM\JoinColumn(name="id_sortie", referencedColumnName="id_sortie") },
     *      inverseJoinColumns = { @ORM\JoinColumn(name = "id_participant", referencedColumnName="id_participant") })
     */
    private $participants;

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

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getIdSortie(): ?int
    {
        return $this->idSortie;
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

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbinscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbinscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participant $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getSiteOrganisateur(): ?Campus
    {
        return $this->siteOrganisateur;
    }

    public function setSiteOrganisateur(?Campus $siteOrganisateur): self
    {
        $this->siteOrganisateur = $siteOrganisateur;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipants(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->addSorties($this);
        }

        return $this;
    }

    public function removeParticipants(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            $participant->removeSorties($this);
        }

        return $this;
    }

    public function __toString(){
        return $this->nom;
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

}
