<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('nbInscriptionsMax')
            ->add('duree')
            ->add('infosSortie', TextareaType::class)

            ->add('ville', EntityType::class, [
                'mapped' => false,
                'class' => Ville::class,
            ])
            ->add('nouveauLieu', LieuType::class, [
                'mapped' => false,
                'label' => false,
            ]);
        ;

        $villeModifier = function (FormInterface $form, Ville $ville = null)
        {
            $lieux = null === $ville ? [] : $ville->getLieux();

            $form->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choices' => $lieux,
            ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($villeModifier) {
            $ville = $event->getForm()->get('ville')->getData();

            $villeModifier($event->getForm(), $ville);
        });

        $builder->get('ville')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($villeModifier) {
            $ville = $event->getForm()->get('ville')->getData();

            $villeModifier($event->getForm()->getParent(), $ville);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
