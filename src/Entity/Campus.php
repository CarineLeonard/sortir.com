<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idCampus;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Le nom ne doit pas être vide !")
     *      @Assert\Length(
     *      max = 30,
     *      maxMessage = "Votre nom ne peut pas être supérieur à {{ limit }} charactères",
     *      allowEmptyString = false
     * )
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="campus", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="id_participant", referencedColumnName="id_participant")
     */
    private $participants;

    public function getIdCampus(): ?int
    {
        return $this->idCampus;
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

    public function __toString(){
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     */
    public function setParticipants($participants): void
    {
        $this->participants = $participants;
    }


}
