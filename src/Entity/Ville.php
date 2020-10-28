<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 * @UniqueEntity(fields = {"nom", "codePostal"})
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idVille;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"lieu"})
     *      @Assert\NotBlank(message="Le nom ne doit pas être vide !")
     *      @Assert\Length(
     *      max = 30,
     *      maxMessage = "Votre nom ne peut pas être supérieur à {{ limit }} charactères",
     *      allowEmptyString = false
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"lieu"})
     *      @Assert\NotBlank(message="Le code postal ne doit pas être vide !")
     *      @Assert\Length(
     *      max = 10,
     *      maxMessage = "Votre code postal ne peut pas être supérieur à {{ limit }} charactères",
     *      allowEmptyString = false
     * )
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity=Lieu::class, mappedBy="ville", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="id_lieu", referencedColumnName="id_lieu")
     */
    private $lieux;

    public function __construct()
    {
        $this->lieux = new ArrayCollection();
    }

    public function getIdVille(): ?int
    {
        return $this->idVille;
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

    public function getCodePostal(): ?string
    {
        return str_replace(' ', '', $this->codePostal);
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = str_replace(' ', '', $codePostal);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLieux()
    {
        return $this->lieux;
    }

    /**
     * @param mixed $lieux
     */
    public function setLieux($lieux): void
    {
        $this->lieux = $lieux;
    }

    public function __toString()
    {
        return $this->nom.' ('.str_replace(' ', '', $this->codePostal).')';
    }
}
