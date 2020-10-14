<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/profil", name="participant_profil")
     */
    public function profil(ParticipantRepository $participantRepository, CampusRepository $campusRepo, Request $request,
                           EntityManagerInterface $em, UserInterface $user)
    {
        //$participant = new Participant();

        $user = $this->getUser();
        $participant = $participantRepository -> findOneBy([
            'mail' => ($user->getUsername())  ]);

        $participant->getNom();
        $participant->getPrenom();
        $participant->getPseudo();
        $participant->getTelephone();
        $participant->getMail();
        $participant->getPassword();
        $participant->getCampus();
        $this->denyAccessUnlessGranted('ROLE_USER');
        $participantForm = $this->createForm(ParticipantType::class, $participant) ;


        $participantForm->handleRequest($request);
        if ($participantForm->isSubmitted() && $participantForm->isValid()) {

            $participant = $participantForm->getData();
            $em->persist($participant);
            $em->flush();
            $this->addFlash('success', 'Votre profil a bien été mis à jour!');
            return $this->redirectToRoute('main_index');
        }
        return $this->render('participant/profil.html.twig', [
            'participantForm' => $participantForm->createView()
        ]);
    }

    /**
     * @Route("/user/{id}", name="participant_user", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function user ($id, Request $request)
    {
        $participantRepo = $this->getDoctrine()->getRepository(Participant::class) ;
        $participant = $participantRepo -> find($id);


        return $this->render('participant/user.html.twig', [
            'controller_name' => 'ParticipantController',
            "participant" => $participant,
        ]);
    }
}
