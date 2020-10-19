<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
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
        $sortie->setDateHeureDebut(\DateTime::createFromFormat('Y-m-d H:i', date('Y-m-d H:i', strtotime('+2 hours'))));
        $sortie->setDateLimiteInscription($sortie->getDateHeureDebut());
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        if ($request->request->all('sortie'))
        {
            $villeId = $request->request->all('sortie')['ville'];
            $ville = $this->getDoctrine()->getRepository(Ville::class)->find($villeId);
            $sortieForm->get('ville')->setData($ville);
        }

        $enregistrer = false;
        $publier = false;
        $etat = null;
        if ($request->request->has('enregistrer'))
        {
            $enregistrer = true;
        }
        if ($request->request->has('publier'))
        {
            $publier = true;
        }
        if ($request->request->has('annuler'))
        {
            return $this->redirectToRoute('main_index', [
            ]);
        }

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
            if ($enregistrer)
            {
                $etat = $etatRepo->findOneBy(['libelle' => 'créée']);
            }
            if ($publier)
            {
                $etat = $etatRepo->findOneBy(['libelle' => 'ouverte']);
            }
            $sortie->setEtat($etat);

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
     * @Route("/modifier/{id}", name="modifier", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function modifier($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class) ;
        $sortie = $sortieRepo -> find($id);

        return $this->render('sortie/modifier.html.twig', [
            'controller_name' => 'SortieController',
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/annuler/{id}", name="annuler", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function annuler($id, Request $reques)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class) ;
        $sortie = $sortieRepo -> find($id);

        return $this->render('sortie/annuler.html.twig', [
            'controller_name' => 'SortieController',
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/afficher/{id}", name="afficher", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function afficher($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class) ;
        $sortie = $sortieRepo -> find($id);

        return $this->render('sortie/afficher.html.twig', [
            'controller_name' => 'SortieController',
            'sortie' => $sortie,
        ]);
    }

}
