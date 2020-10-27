<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SortieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sortie::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Sortie')
            ->setEntityLabelInPlural('Sorties')
            ->setPageTitle('index', '%entity_label_plural%')
            // de base recherche sur tous les champs
            ->setDefaultSort(['dateHeureDebut' => 'ASC'])
            ->setPaginatorPageSize(30)
            ->setDateTimeFormat('dd/MM/Y HH:mm')
            ->setDateFormat('d/m/Y')
            ->setNumberFormat('%d');
        // ->setEntityPermission('ROLE_EDITOR')
        ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('idSortie')->onlyOnIndex(),
            TextField::new('nom'),
            DateTimeField::new('dateHeureDebut'),
            NumberField::new('duree'),
            DateTimeField::new('dateLimiteInscription'),
            NumberField::new('nbInscriptionsMax'),
            TextareaField::new('infosSortie')->hideOnIndex(),
            AssociationField::new('organisateur'),
            AssociationField::new('lieu'),
            AssociationField::new('etat'),
            AssociationField::new('siteOrganisateur'),
            AssociationField::new('participants'),
        ];
    }

    // réécrire //deleteEntity ?
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->addFlash('danger', 'Vous ne pouvez pas supprimer une sortie!');
        return;
    }

}
