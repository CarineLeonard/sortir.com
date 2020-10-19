<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie: '
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de la sortie: ',
            ])
            ->add('duree', NumberType::class, [
                'label' => 'Durée: '
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date limite d\'inscriptions: ',
            ])
            ->add('nbInscriptionsMax', NumberType::class, [
                'label' => 'Nombres de places: ',
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos: ',
                'attr' => ['rows = 6'],
            ])
            ->add('lieu', EntityType::class, [
                'mapped' => true,
                'class' => Lieu::class,
                'choice_label' => 'nom',
            ])
            ->add('siteOrganisateur', EntityType::class, [
                'mapped' => true,
                'class' => Campus::class,
                'choice_label' => 'nom',
                'disabled' => true,
                //'is_granted_attribute' => 'ROLE_ADMIN',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
