<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/villes", name="admin_villes")
     */
    public function villes()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/villes.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/campus", name="admin_campus")
     */
    public function campus()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/campus.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

}
