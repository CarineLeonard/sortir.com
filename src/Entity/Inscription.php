<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionRepository::class)
 */
class Inscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_inscription;

    /**
     * @ORM\ManyToOne(targetEntity=Sortie::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $sortie_no_sortie;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $participant_no_participant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $date_inscription): self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    public function getSortieNoSortie(): ?Sortie
    {
        return $this->sortie_no_sortie;
    }

    public function setSortieNoSortie(?Sortie $sortie_no_sortie): self
    {
        $this->sortie_no_sortie = $sortie_no_sortie;

        return $this;
    }

    public function getParticipantNoParticipant(): ?Participant
    {
        return $this->participant_no_participant;
    }

    public function setParticipantNoParticipant(?Participant $participant_no_participant): self
    {
        $this->participant_no_participant = $participant_no_participant;

        return $this;
    }
}
