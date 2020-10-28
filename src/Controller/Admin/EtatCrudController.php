<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Entity\Etat;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EtatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etat::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Etat')
            ->setEntityLabelInPlural('Etats')
            ->setPageTitle('index', '%entity_label_plural%')
            // de base recherche sur tous les champs
            ->setDefaultSort(['idEtat' => 'ASC'])
            ->setPaginatorPageSize(30)
            ->setDateTimeFormat('d/m/Y H:i')
            ->setDateFormat('d/m/Y')
            ->setTimezone('Europe/Paris')
            ->setNumberFormat('%.2d');
        // ->setEntityPermission('ROLE_EDITOR')
        ;
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

            $this->addFlash('danger', 'Vous ne pouvez pas supprimer un état!');
            return;

    }
}
