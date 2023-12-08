<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\FieldType\ImageMap;

use Ibexa\Core\FieldType\Image\Value as ImageValue;

/**
 * Value for ImageMap field type.
 */
class Value extends ImageValue
{
    public array $map = [];
}
