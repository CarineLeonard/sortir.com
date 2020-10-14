<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
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

            $sortiesRepo = $this->getDoctrine()->getRepository(Sortie::class) ;
            $sorties = $sortiesRepo->findAll([]);

            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                "sorties" => $sorties,
            ]);



        } else {
            return $this->render('security/login.html.twig');
        }

    }

}
