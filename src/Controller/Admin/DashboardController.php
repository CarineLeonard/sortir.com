<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Entity\Csv;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\CsvType;
use App\Service\CSVReader;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()->getRepository(Participant::class)->count([]);
        $sorties = $this->getDoctrine()->getRepository(Sortie::class)->count([]);
        return $this->render('admin/my-dashboard.html.twig', [
            'users' => $users,
            'sorties' => $sorties,
        ]);
    }

     public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sortir');
    }

    public function configureMenuItems(): iterable
    {
        return [
            // liens vers les 'index' action mais on peut faire des liens 'crud' et faire options de tris , route symfony ou url
            //https://symfony.com/doc/master/bundles/EasyAdminBundle/dashboards.html
         MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
         MenuItem::section('Utilisateurs'),
            MenuItem::linkToCrud('Participant', 'fas fa-user', Participant::class),
            MenuItem::linkToCrud('Campus', 'fas fa-university', Campus::class),
         MenuItem::section('Sorties'),
            MenuItem::linkToCrud('Sortie', 'fas fa-glass-cheers', Sortie::class),
            MenuItem::linkToCrud('Etat', 'fas fa-battery-half', Etat::class),
            MenuItem::linkToCrud('Lieu', 'fas fa-map-marker-alt', Lieu::class),
            MenuItem::linkToCrud('Ville', 'far fa-building', Ville::class),
         MenuItem::section('Autres'),
            MenuItem::linkToLogout('Logout', 'fas fa-times-circle'),
            MenuItem::linkToRoute('Accueil', 'fas fa-wifi', 'main_index'),
        ];
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getUsername())
            // ->setAvatarUrl($user->getProfileImageUrl())
            // attention quand email ou pseudo !
            ->setGravatarEmail($user->getUsername())
            ->addMenuItems([
                MenuItem::linkToRoute('Mon profil', 'fa fa-id-card', 'participant_profil'),
                MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            ]);
    }

    public function configureCrud(): Crud
    {
        return Crud::new();
    }

    /**
     * @Route("/admin/csv", name="admin_addCSVFile")
     */
    public function addCSVFile(Request $request, FileUploader $fileUploader, CSVReader $reader, EntityManagerInterface $em) : Response
    {

        $csv = new Csv();
        $CsvForm = $this->createForm(CsvType::class, $csv);

        $CsvForm->handleRequest($request);
        if ($CsvForm->isSubmitted() && $CsvForm->isValid())
        {
            /** @var UploadedFile $csvFile */
            $csvFile = $CsvForm->get('csv')->getData();

            if($csvFile)
            {
               $csvFileName = $fileUploader->upload($csvFile);
               $csv->setCsvFileName($fileUploader->getTargetDirectory().'data/'.$csvFileName);
            }
            // ... persist the $csv variable or any other work : service pour parser le csv et ajouter à la bdd !
            // ici tu met le moulinage du csv
            try {
                $reader->ReadCSV($csv, $em);
                $this->addFlash('success', 'Vos contacts ont bien été ajoutés !');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Vos contacts n\'ont pas pu être ajoutés : votre fichier doit contenir les colonnes : 
                nom, prenom, telephone, mail, motPasse, administrateur, actif, campus, pseudo : dans cet ordre et avec en-tete');
            }

            return $this->redirectToRoute('admin_index');

        }
        return $this->render('admin/csv.html.twig', [
            'CsvForm' => $CsvForm->createView(),
        ]);
    }
}
