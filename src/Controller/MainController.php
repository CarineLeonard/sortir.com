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

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_index")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ('ROLE_USER') {
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        } else {
            return $this->render('security/login.html.twig');
        }

    }

    /**
     * @Route("/profil", name="main_profil")
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
        return $this->render('main/profil.html.twig', [
            'participantForm' => $participantForm->createView()
        ]);

    }

}
