<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Entity\Lieu;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LieuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lieu::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Lieu')
            ->setEntityLabelInPlural('Lieux')
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
            IdField::new('id')->onlyOnIndex(),
            TextField::new('nom'),
            TextField::new('rue'),
            TextField::new('nom'),
            NumberField::new('latitude'),
            NumberField::new('longitude'),
            AssociationField::new('ville'),
            AssociationField::new('sorties')
        ];
    }

    // réécrire //deleteEntity : il faut un one to many dans lieux !
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Lieu $entity */
        $entity = $entityInstance;

        if (!($entity->getSorties()->isEmpty())) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer un lieu utilisé!');
            return;
        } else {
            $entityManager->remove($entityInstance);
            $entityManager->flush();
        }
    }

}
