<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idLieu;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Veuillez renseigner un nom !")
     * @Groups({"lieu"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Veuillez renseigner une rue !")
     * @Groups({"lieu"})
     */
    private $rue;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank(message="Veuillez renseigner une latitude !")
     * @Assert\Range(
     *      min = -90,
     *      max = 90,
     *      notInRangeMessage = "La latitude doit être comprise entre {{ min }} et {{ max }}.",
     * )
     * @Groups({"lieu"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank(message="Veuillez renseigner une longitude !")
     * @Assert\Range(
     *      min = -180,
     *      max = 180,
     *      notInRangeMessage = "La longitude doit être comprise entre {{ min }} et {{ max }}.",
     * )
     * @Groups({"lieu"})
     */
    private $longitude;

    // attention avoir le nom des champs dans join column !
    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="lieux", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="id_ville", referencedColumnName="id_ville")
     * @Assert\NotBlank(message="Veuillez sélectionner une ville !")
     * @Groups({"lieu"})
     */
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="lieu", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="id_sortie", referencedColumnName="id_sortie")
     */
    private $sorties;

    public function getId(): ?int
    {
        return $this->idLieu;
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

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getSorties()
    {
        return $this->sorties;
    }

    /**
     * @param mixed $sorties
     */
    public function setSorties($sorties): void
    {
        $this->sorties = $sorties;
    }



}
