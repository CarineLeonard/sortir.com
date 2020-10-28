<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Form\SortieUpdateType;
use App\Form\VilleType;
use App\Service\EtatsSortieService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            $etat = null;
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
        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);

        return $this->render('sortie/nouvelle.html.twig', [
            'controller_name' => 'SortieController',
            'sortieForm' => $sortieForm->createView(),
            'lieuForm' => $lieuForm->createView(),
            'villeForm' => $villeForm->createView(),
        ]);
    }

    /**
     * @Route("/afficher/{id}", name="afficher", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function afficher($id, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class) ;
        $sortie = $sortieRepo -> find($id);
        $etatsSortieService = new EtatsSortieService($em);
        $etatsSortieService->updateEtat($sortie);

        $datetime1Mois = date_modify(new \DateTime(), '-1 month');
        if ($sortie->getDateHeureDebut() >= $datetime1Mois) {
            return $this->render('sortie/afficher.html.twig', [
                'controller_name' => 'SortieController',
                'sortie' => $sortie,
            ]);
        } else {
            $this->addFlash('danger', 'La sortie est trop ancienne pour être affichée !');
            return $this->redirectToRoute('main_index', [
            ]);
        }

    }

    /**
     * @Route("/modifier/{id}", name="modifier", requirements={"id"="\d+"})
     */
    public function modifier($id, Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class) ;
        $sortie = $sortieRepo -> find($id);
        $etatsSortieService = new EtatsSortieService($em);
        $etatsSortieService->updateEtat($sortie);
        $sortie->getLieu()->getVille();
        $sortie->getLieu();

        $lieuRepo = $this->getDoctrine()->getRepository(Lieu::class) ;
        $lieu = $lieuRepo -> find($sortie->getLieu()->getId());

        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $sortieForm = $this->createForm(SortieUpdateType::class, $sortie);

        $publier = false;
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
            if ($request->request->has('supprimer'))
            {
                $em->remove($sortie);
                $em->flush();

                $this->addFlash('success', 'La sortie a été supprimée !');
            } else {

                $etat = $sortie->getEtat()->getLibelle();
                $sortie = $sortieForm->getData();
                $etatRepo = $this->getDoctrine()->getRepository(Etat::class);

                if ($publier)
                {
                    $etat = $etatRepo->findOneBy(['libelle' => 'ouverte']);
                    $sortie->setEtat($etat);
                }
                $em->persist($sortie);
                $em->flush();

                $this->addFlash('success', 'La sortie a été modifiée !');

            }

            return $this->redirectToRoute('main_index', [
            ]);
        }


        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);

        return $this->render('sortie/modifier.html.twig', [
            'controller_name' => 'SortieController',
            'sortieForm' => $sortieForm->createView(),
            'lieuForm' => $lieuForm->createView(),
            'villeForm' => $villeForm->createView(),
            'sortie' => $sortie,
            'lieu' => $lieu,
        ]);
    }

    /**
     * @Route("/annuler/{id}", name="annuler", requirements={"id"="\d+"})
     */
    public function annuler($id, Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class) ;
        $sortie = $sortieRepo->find($id);
        $etatsSortieService = new EtatsSortieService($em);
        $etatsSortieService->updateEtat($sortie);

        /** @var Participant $user */
        $user = $this->getUser();

        $etatSortie = $sortie->getEtat()->getLibelle();

        if (($etatSortie != 'ouverte' && $etatSortie != 'clôturée')
            || $sortie->getDateHeureDebut() < new \DateTime()
            || ($sortie->getOrganisateur() !== $user && !in_array('ROLE_ADMIN', $user->getRoles())))
        {
            $this->addFlash('danger', 'Vous ne pouvez pas annuler cette sortie !');
            return $this->redirect($request->headers->get('referer'));
        }

        $infosSortie = $sortie->getInfosSortie();
        $sortie->setInfosSortie('');

        $sortieForm = $this->createFormBuilder($sortie)
            ->add('infosSortie', TextareaType::class)
            ->getForm()
        ;

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            $etatRepo = $this->getDoctrine()->getRepository(Etat::class);

            $etat = $etatRepo->findOneBy(['libelle' => 'annulée']);
            $sortie->setEtat($etat);

            $sortie->setInfosSortie('[Motif de l\'annulation : '.$sortie->getInfosSortie()."]\n".$infosSortie);

            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'La sortie a été annulée !');
            return $this->redirectToRoute('main_index', []);
        }

        return $this->render('sortie/annuler.html.twig', [
            'controller_name' => 'SortieController',
            'sortie' => $sortie,
            'sortieForm' => $sortieForm->createView(),
        ]);
    }

    /**
     * @Route("/publier/{id}", name="publier", requirements={"id"="\d+"})
     */
    public function publier($id, EntityManagerInterface $em, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class) ;
        $sortie = $sortieRepo->find($id);
        $etatsSortieService = new EtatsSortieService($em);
        $etatsSortieService->updateEtat($sortie);

        /** @var Participant $user */
        $user = $this->getUser();

        $isOrganisateur = $sortie->getOrganisateur() === $user;

        $etatSortie = $sortie->getEtat()->getLibelle();
        if ($etatSortie == 'créée' && $isOrganisateur)
        {
            $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
            $sortie->setEtat($etatRepo->findOneBy(['libelle' => 'ouverte']));

            $em->persist($sortie);
            $em->flush();

            $etatsSortieService->updateEtat($sortie);

            $this->addFlash('success', 'Vous avez publié la sortie !');
        }
        else if(!$isOrganisateur)
        {
            $this->addFlash('danger', 'Vous ne pouvez pas publier une sortie sans en être l\'organisateur !');
        }
        else
        {
            $this->addFlash('danger', 'Vous ne pouvez pas publier une sortie'.$etatSortie.' !');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/inscription/{id}", name="inscription", requirements={"id"="\d+"})
     */
    public function inscription($id, EntityManagerInterface $em, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class) ;
        $sortie = $sortieRepo->find($id);
        $etatsSortieService = new EtatsSortieService($em);
        $etatsSortieService->updateEtat($sortie);

        /** @var Participant $user */
        $user = $this->getUser();

        $isParticipant = $sortie->getParticipants()->contains($user);

        $etatSortie = $sortie->getEtat()->getLibelle();
        if ($etatSortie == 'ouverte' && !$isParticipant)
        {
            $sortie->addParticipants($user);

            $em->persist($sortie);
            $em->flush();

            $etatsSortieService->updateEtat($sortie);

            $this->addFlash('success', 'Vous vous êtes inscrit à une sortie !');
        }
        else if($isParticipant)
        {
            $this->addFlash('danger', 'Vous êtes déjà inscrit à cette sortie !');
        }
        else
        {
            $this->addFlash('danger', 'Vous ne pouvez pas vous inscrire à une sortie'.$etatSortie.' !');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/desistement/{id}", name="desistement", requirements={"id"="\d+"})
     */
    public function desistement($id, EntityManagerInterface $em, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class) ;
        $sortie = $sortieRepo->find($id);
        $etatsSortieService = new EtatsSortieService($em);
        $etatsSortieService->updateEtat($sortie);

        /** @var Participant $user */
        $user = $this->getUser();

        $isParticipant = $sortie->getParticipants()->contains($user);

        $etatSortie = $sortie->getEtat()->getLibelle();
        if (($etatSortie == 'ouverte' || $etatSortie == 'clôturée') && $isParticipant)
        {
            $sortie->removeParticipants($user);

            $em->persist($sortie);
            $em->flush();

            $etatsSortieService->updateEtat($sortie);

            $this->addFlash('success', 'Vous vous êtes désisté d\'une sortie !');
        }
        else if(!$isParticipant)
        {
            $this->addFlash('danger', 'Vous ne pouvez pas vous désister d\'une sortie sans y être inscrit !');
        }
        else
        {
            $this->addFlash('danger', 'Vous ne pouvez pas vous désister d\'une sortie'.$etatSortie.' !');
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
