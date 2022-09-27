<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MapAreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shape', ChoiceType::class, [
                'choices' => [
                    'onisep.imagemap.maparea.shape.rect' => 'rect',
                    'onisep.imagemap.maparea.shape.circle' => 'circle',
                    'onisep.imagemap.maparea.shape.poly' => 'poly',
                    'onisep.imagemap.maparea.shape.full' => 'default',
                ],
                'label' => 'onisep.imagemap.maparea.shape.label',
            ])
            ->add('position', TextType::class, [
                'label' => 'onisep.imagemap.maparea.coordinates.label',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'onisep.imagemap.maparea.description.label',
            ])
            ->add('link', LinkType::class, [
                'label' => 'onisep.imagemap.maparea.link.label',
            ])
            ->add('target', ChoiceType::class, [
                'choices' => [
//                    'Fenêtre en cours' => '_realSelf',
                    'onisep.imagemap.maparea.target.self' => '_self',
                    'onisep.imagemap.maparea.target.blank' => '_blank',
//                    'Popin' => 'popin',
                ],
                'label' => 'onisep.imagemap.maparea.target.label',
            ])
            ->add('anchor', TextType::class, [
                'required' => false,
                'label' => 'onisep.imagemap.maparea.anchor.label',
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'imagemap_map_area';
    }
}
