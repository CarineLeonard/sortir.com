<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class VilleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ville::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Ville')
            ->setEntityLabelInPlural('Villes')
            ->setPageTitle('index', '%entity_label_plural%')
            // de base recherche sur tous les champs
            ->setDefaultSort(['nom' => 'ASC'])
            ->setPaginatorPageSize(30)
            ->setDateTimeFormat('d/m/Y H:i')
            ->setDateFormat('d/m/Y')
            ->setTimezone('Europe/Paris')
            ->setNumberFormat('%.2d');
        // ->setEntityPermission('ROLE_EDITOR')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('idVille')->onlyOnIndex(),
            TextField::new('nom'),
            TextField::new('codePostal'),
            AssociationField::new('lieux')
        ];
    }

    // réécrire //deleteEntity ?
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Ville $entity */
        $entity = $entityInstance;

        if (!($entity->getLieux()->isEmpty())) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer une ville utilisée!');
            return;
        } else {
            $entityManager->remove($entityInstance);
            $entityManager->flush();
        }
    }
}
