<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/profil", name="participant_profil")
     */
    public function profil(ParticipantRepository $participantRepository, Request $request,
                           EntityManagerInterface $em, FileUploader $fileUploader, UserPasswordEncoderInterface $encoder)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var Participant $user */
        $user = $this->getUser();
        $participant = $participantRepository -> findOneBy([
            'mail' => ($user->getUsername())
        ]);
        $participantForm = $this->createForm(ParticipantType::class, $participant) ;

        $participantForm->handleRequest($request);
        if ($participantForm->isSubmitted() && $participantForm->isValid()) {

            /** @var UploadedFile $imageFile */
            $imageFile = $participantForm->get('image')->getData();

            if ($imageFile)
            {
                $imageFileName = $fileUploader->upload($imageFile);
                $participant->setImageFilename($imageFileName);
            }

            $participant->setMotPasse($encoder->encodePassword($participant, $participant->getPassword()));

            // ... persist the $product variable or any other work
            $em->persist($participant);
            $em->flush();
            $this->addFlash('success', 'Votre profil a bien été mis à jour!');
            return $this->redirectToRoute('main_index');
        }
        return $this->render('participant/profil.html.twig', [
            'participantForm' => $participantForm->createView(),
            'participant' => $participant,
        ]);
    }

    /**
     * @Route("/user/{id}", name="participant_user", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function user ($id)
    {
        $participantRepo = $this->getDoctrine()->getRepository(Participant::class) ;
        $participant = $participantRepo -> find($id);

        return $this->render('participant/user.html.twig', [
            'controller_name' => 'ParticipantController',
            "participant" => $participant,
        ]);
    }
}
