<?php

declare(strict_types=1);

namespace Onisep\ImageMapBundle\FieldType\ImageMap;

use eZ\Publish\Core\FieldType\Image\Value as ImageValue;

/**
 * Value for ImageMap field type.
 */
class Value extends ImageValue
{
    public array $map;
}
