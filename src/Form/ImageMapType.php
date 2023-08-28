<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\Form;

use EzSystems\RepositoryForms\Form\Type\FieldType\ImageFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ImageMapType extends AbstractType
{
    public function getParent()
    {
        return ImageFieldType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('map', MapAreasType::class, [
                'entry_type' => MapAreaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'label' => 'onisep.imagemap.zones.label',
                'prototype_data' => [
                    'shape' => 'rect',
                    'position' => '0,0,100,100',
                ],
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'imagemap';
    }
}
