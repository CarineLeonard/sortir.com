<?php

namespace App\Form\Extension;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Flex\Options;


class SecurityExtension extends AbstractTypeExtension
{
    private $authorizationChecker;
    private $propertyAccessor;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, PropertyAccessorInterface $propertyAccessor)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function getExtendedType()
    {
        return EntityType::class;
    }

    private function isGranted(FormInterface $form, array $options)
    {
        if (!$options['is_granted_attribute']) {
            return true;
        }

        if ($this->authorizationChecker->isGranted($options['is_granted_attribute'])) {
            return true;
        }

        return false;
    }

    private function disableView(FormView $view)
    {
        $view->vars['attr']['disabled'] = true;

        foreach ($view as $child) {
            $this->disableView($child);
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['is_granted_attribute']) {
            return;
        }

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
            if ($this->isGranted($options)) {
                return;
            }

            $event->setData($event->getForm()->getViewData());
        });
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method iterable getExtendedTypes()
    }

    public static function getExtendedTypes(): iterable
    {
        // return FormType::class to modify (nearly) every field in the system
        return [ EntityType::class];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'is_granted_attribute' => null,
            'disabled' => function (Options $options) {
                return $this->isGranted($options);
            },
        ]);
    }
}