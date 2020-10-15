<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\RechercheSortieType;
use App\Repository\SortieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_index")
     */
    public function index(Request $request, SortieRepository $sortiesRepo)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ('ROLE_USER') {

            $user = $this->getUser();
            $data = new SearchData();
            $data->page = $request->get('page', 1);
            $form = $this->createForm(RechercheSortieType::class, $data);
            $form->handleRequest($request);
            $sorties = $sortiesRepo->findSearch($data, $user);
            return $this->render('main/index.html.twig', [
                'sorties' => $sorties,
                'form' => $form->createView()
            ]);

            /*$rechercheForm = $this->createForm(RechercheSortieType::class);
            $rechercheForm->handleRequest($request);

            $sorties = $sortiesRepo->findAll();

            if ($rechercheForm->isSubmitted() && $rechercheForm->isValid()) {
                $nom = $rechercheForm->getData()->getNom();
                $rechercheNom = $sortiesRepo->search($nom);

                if ($rechercheNom == null) {
                    $this->addFlash('erreur', 'Aucune sortie trouvée.');
                }
            }

            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'sorties' => $sorties,
                'rechercheForm' => $rechercheForm->createView()
            ]); */

        } else {
            return $this->render('security/login.html.twig');
        }

    }

}
