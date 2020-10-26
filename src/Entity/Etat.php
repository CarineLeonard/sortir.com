<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 */
class Etat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idEtat;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Le nom ne doit pas être vide !")
     *      @Assert\Length(
     *      max = 30,
     *      maxMessage = "Votre libelle ne peut pas être supérieur à {{ limit }} charactères",
     *      allowEmptyString = false
     * )
     */
    private $libelle;

    public function getIdEtat(): ?int
    {
        return $this->idEtat;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function __toString(){
        return $this->libelle;
    }
}
