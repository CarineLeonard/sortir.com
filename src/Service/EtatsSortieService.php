<?php

namespace App\Service;

use App\Entity\Etat;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;

class EtatsSortieService
{
    private $em;
    private $etatsRepo;
    private $etats;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->etatsRepo = $em->getRepository(Etat::class);
        $this->etats = $this->etatsRepo->findAll();
        foreach ($this->etats as $etat)
        {
            $this->etats[$etat->getLibelle()] = $etat;
        }
    }

    public function updateEtat(Sortie $sortie)
    {
        if ($sortie->getEtat() == 'annulée' || $sortie->getEtat() == 'créée')
        {
            return;
        }

        $now = new \DateTime();

        if ($sortie->getEtat() == 'clôturée'
            && $sortie->getParticipants()->count() < $sortie->getNbinscriptionsMax()
            && $sortie->getDateLimiteInscription() > $now)
        {
            $sortie->setEtat($this->etats['ouverte']);
        }
        elseif ($sortie->getDateLimiteInscription() <= $now
            || $sortie->getParticipants()->count() >= $sortie->getNbinscriptionsMax())
        {
            $sortie->setEtat($this->etats['clôturée']);
        }
        elseif ($sortie->getDateHeureDebut() <= $now)
        {
            $sortie->setEtat($this->etats['en cours']);
        }
        elseif ($sortie->getDateHeureDebut() <= $now->modify('-'.$sortie->getDuree().' minutes'))
        {
            $sortie->setEtat($this->etats['passée']);
        }
        elseif ($sortie->getDateHeureDebut() <= $now->modify('-1 month'))
        {
            $sortie->setEtat($this->etats['historisée']);
        }

        $this->em->persist($sortie);
        $this->em->flush();
    }

    public function updateEtatWithoutPersist(Sortie $sortie)
    {
        if ($sortie->getEtat() == 'annulée' || $sortie->getEtat() == 'créée')
        {
            return;
        }

        $now = new \DateTime();

        if ($sortie->getEtat() == 'clôturée'
            && $sortie->getParticipants()->count() < $sortie->getNbinscriptionsMax()
            && $sortie->getDateLimiteInscription() < $now)
        {
            $sortie->setEtat($this->etats['ouverte']);
        }
        elseif ($sortie->getDateLimiteInscription() >= $now
            || $sortie->getParticipants()->count() >= $sortie->getNbinscriptionsMax())
        {
            $sortie->setEtat($this->etats['clôturée']);
        }
        elseif ($sortie->getDateHeureDebut() <= $now)
        {
            $sortie->setEtat($this->etats['en cours']);
        }
        elseif ($sortie->getDateHeureDebut() <= $now->modify('-'.$sortie->getDuree().' minutes'))
        {
            $sortie->setEtat($this->etats['passée']);
        }
        elseif ($sortie->getDateHeureDebut() <= $now->modify('-1 month'))
        {
            $sortie->setEtat($this->etats['historisée']);
        }
    }
}