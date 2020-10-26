<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\RechercheSortieType;
use App\Repository\SortieRepository;
use App\Service\EtatsSortieService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_index")
     */
    public function index(Request $request, SortieRepository $sortiesRepo, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ('ROLE_USER') {

            $date = new \DateTime();
            $data = new SearchData();
            //$data->page = $request->get('page', 1);
            $form = $this->createForm(RechercheSortieType::class, $data);

            $form->handleRequest($request);

            $data->campus = $form->get('campus')->getData();

            $sorties = $sortiesRepo->findSearch($data, $this->getUser());

            $etatsSortieService = new EtatsSortieService($em);
            foreach ($sorties as $sortie)
            {
                $etatsSortieService->updateEtat($sortie);
            }

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
