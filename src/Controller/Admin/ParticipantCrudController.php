<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ParticipantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Participant::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Participant')
            ->setEntityLabelInPlural('Participants')
            ->setPageTitle('index', '%entity_label_plural%')
            // de base recherche sur tous les champs
            ->setDefaultSort(['nom' => 'ASC'])
            ->setPaginatorPageSize(30)
            ->setDateTimeFormat('d/m/Y H:i')
            ->setDateFormat('d/m/Y')
            ->setTimezone('Europe/Paris')
            ->setNumberFormat('%.2d');
        // ->setEntityPermission('ROLE_EDITOR')
        // set mot de passe ??
        ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('pseudo'),
            TelephoneField::new('telephone'),
            EmailField::new('mail'),
            TextField::new('newPassword')->hideOnIndex(),
            BooleanField::new('administrateur'),
            BooleanField::new('actif'),
            AssociationField::new('campus'),
            AssociationField::new('sorties'),
            TextField::new('imageFilename')->hideOnIndex(),
        ];
    }


    // réécrire //deleteEntity ?
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Participant $entity */
        $entity = $entityInstance;

        if (!($entity->getSorties()->isEmpty())) {
            $entity->setActif(false);
            dump($entity);
            $entityManager->persist($entity);
            $entityManager->flush();
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer un participant utilisé!');
            return;
        } else {
            $entityManager->remove($entityInstance);
            $entityManager->flush();
        }

    }


}
