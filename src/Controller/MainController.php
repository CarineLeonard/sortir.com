<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}
