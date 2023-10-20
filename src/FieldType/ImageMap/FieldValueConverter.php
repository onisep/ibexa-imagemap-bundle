<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\FieldType\ImageMap;

use Ibexa\Contracts\Core\Persistence\Content\Type\FieldDefinition;
use Ibexa\Core\FieldType\FieldSettings;
use Ibexa\Core\Persistence\Legacy\Content\FieldValue\Converter\ImageConverter;
use Ibexa\Core\Persistence\Legacy\Content\StorageFieldDefinition;

class FieldValueConverter extends ImageConverter
{
    public function toStorageFieldDefinition(FieldDefinition $fieldDef, StorageFieldDefinition $storageDef): void
    {
        $storageDef->dataText5 = serialize($fieldDef->fieldTypeConstraints->fieldSettings['selectionContentTypes'] ?? []);
    }

    public function toFieldDefinition(StorageFieldDefinition $storageDef, FieldDefinition $fieldDef): void
    {
        $fieldDef->fieldTypeConstraints->fieldSettings = new FieldSettings([
            'selectionContentTypes' => unserialize($storageDef->dataText5),
        ]);
    }
}
