<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CampusCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Campus::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Campus')
            ->setEntityLabelInPlural('Campus')
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
            IdField::new('id_campus')->onlyOnIndex(),
            TextField::new('nom'),
            AssociationField::new('participants'),
        ];
    }

    // réécrire //deleteEntity : il faut un onetomany dans campus !
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Campus $entity */
        $entity = $entityInstance;

        if (!($entity->getParticipants()->isEmpty())) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer un campus utilisé !');
            return;
        } else {
            $entityManager->remove($entityInstance);
            $entityManager->flush();
        }
    }

}
