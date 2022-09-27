<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\DataTransformer;

use EzSystems\EzPlatformContentForms\FieldType\DataTransformer\ImageValueTransformer;
use Onisep\IbexaImageMapBundle\FieldType\ImageMap\Value;

class ImageMapTransformer extends ImageValueTransformer
{
    public function transform($value)
    {
        return parent::transform($value) + ['map' => $value->map];
    }

    public function reverseTransform($value)
    {
        /** @var Value $valueObject */
        $valueObject = parent::reverseTransform($value);
        $valueObject->map = $value['map'];

        return $valueObject;
    }
}
