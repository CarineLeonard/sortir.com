<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\RechercheSortieType;
use App\Repository\SortieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_index")
     */
    public function index(Request $request, SortieRepository $sortiesRepo, UserInterface $user)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ('ROLE_USER') {

            $date = new \DateTime();
            $data = new SearchData();
            $data->page = $request->get('page', 1);
            $form = $this->createForm(RechercheSortieType::class, $data);
            $form->handleRequest($request);
            $sorties = $sortiesRepo->findSearch($data, $user);
            return $this->render('main/index.html.twig', [
                'sorties' => $sorties,
                'date' => $date,
                'form' => $form->createView()
            ]);

        } else {
            return $this->render('security/login.html.twig');
        }

    }

}
