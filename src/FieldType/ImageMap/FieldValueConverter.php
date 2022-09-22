<?php

declare(strict_types=1);

namespace Onisep\ImageMapBundle\FieldType\ImageMap;

use eZ\Publish\Core\FieldType\FieldSettings;
use eZ\Publish\Core\Persistence\Legacy\Content\FieldValue\Converter\ImageConverter;
use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldDefinition;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;

class FieldValueConverter extends ImageConverter
{
    public function toStorageFieldDefinition(FieldDefinition $fieldDef, StorageFieldDefinition $storageDef): void
    {
        $storageDef->dataText5 = serialize($fieldDef->fieldTypeConstraints->fieldSettings['selectionContentTypes']);
    }

    public function toFieldDefinition(StorageFieldDefinition $storageDef, FieldDefinition $fieldDef): void
    {
        $fieldDef->fieldTypeConstraints->fieldSettings = new FieldSettings([
            'selectionContentTypes' => unserialize($storageDef->dataText5),
        ]);
    }
}
