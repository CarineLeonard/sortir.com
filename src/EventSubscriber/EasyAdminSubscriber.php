<?php
namespace App\EventSubscriber;

use App\Entity\Participant;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class EasyAdminSubscriber implements EventSubscriberInterface {

    private $slugger ;
    private $encoder ;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setParticipant'],
            BeforeEntityUpdatedEvent::class => ['updateParticipant'],
        ];
    }

    // pour create personnalisé sur easyadmin
    public function setParticipant(BeforeEntityPersistedEvent $event) {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Participant)) {
            return;
        }
        $motPasse = $this->slugger->slug($entity->getNewPassword());
        $entity->setMotPasse($this->encoder->encodePassword($entity, $motPasse));
    }

    // pour update perso sur easy admin
    public function updateParticipant(BeforeEntityUpdatedEvent $event) {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Participant)) {
            return;
        }
        if ($entity->getNewPassword()) {
            $motPasse = $this->slugger->slug($entity->getNewPassword());
            $entity->setMotPasse($this->encoder->encodePassword($entity, $motPasse));
        }
    }
}