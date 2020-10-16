<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\LieuType;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/nouvelle", name="nouvelle")
     */
    public function nouvelle(Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $sortie = new Sortie();
        $sortie->setDateHeureDebut(new \DateTime());
        $sortie->setDateLimiteInscription(new \DateTime());
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
            $etat = $etatRepo->findBy(['libelle' => 'créée']);
            $sortie->setEtat($etat[0]);

            /** @var Participant $organisateur */
            $organisateur = $this->getUser();
            $sortie->setOrganisateur($organisateur);
            $sortie->setSiteOrganisateur($organisateur->getCampus());

            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'La sortie a été enregistrée !');
            return $this->redirectToRoute('main_index', [
            ]);
        }

        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);

        return $this->render('sortie/nouvelle.html.twig', [
            'controller_name' => 'SortieController',
            'sortieForm' => $sortieForm->createView(),
            'lieuForm' => $lieuForm->createView(),
        ]);
    }

    /**
     * @Route("/modifier", name="modifier")
     */
    public function modifier()
    {

        return $this->render('sortie/modifier.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route("/annuler", name="annuler")
     */
    public function annuler()
    {

        return $this->render('sortie/annuler.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route("/afficher/{id}", name="afficher", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function afficher($id, Request $request)
    {

        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class) ;
        $sortie = $sortieRepo -> find($id);

        return $this->render('sortie/afficher.html.twig', [
            'controller_name' => 'SortieController',
            'sortie' => $sortie,
        ]);
    }

}
