<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MapAreasType extends AbstractType
{
    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'imagemap_map_areas';
    }
}
