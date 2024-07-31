<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\FieldType\ImageMap;

use Ibexa\Contracts\Core\FieldType\Value as SPIValue;
use Ibexa\Contracts\Core\Persistence\Content\FieldValue as PersistenceValue;
use Ibexa\Core\FieldType\Image\Type as ImageType;

/**
 * The ImageMap field type.
 */
class Type extends ImageType
{
    public function getFieldTypeIdentifier()
    {
        return 'imagemap';
    }

    public function validateFieldSettings($fieldSettings): array
    {
        return [];
    }

    public function fromHash($hash)
    {
        return empty($hash) ? $this->getEmptyValue() : new Value($hash);
    }

    public function toHash(SPIValue $value)
    {
        $parentHash = parent::toHash($value);
        if (null === $parentHash) {
            $parentHash = [];
        }

        return $parentHash + ['map' => $value->map];
    }

    public function getEmptyValue()
    {
        return new Value();
    }

    public function fromPersistenceValue(PersistenceValue $fieldValue)
    {
        if (null === $fieldValue->data) {
            return $this->getEmptyValue();
        }

        return $this->fromHash([
            'id' => $fieldValue->data['id'] ?? null,
            'alternativeText' => $fieldValue->data['alternativeText'] ?? null,
            'fileName' => $fieldValue->data['fileName'] ?? null,
            'fileSize' => $fieldValue->data['fileSize'] ?? null,
            'uri' => $fieldValue->data['uri'] ?? null,
            'imageId' => $fieldValue->data['imageId'] ?? null,
            'width' => $fieldValue->data['width'] ?? null,
            'height' => $fieldValue->data['height'] ?? null,
            'additionalData' => $fieldValue->data['additionalData'] ?? [],
            'map' => $fieldValue->data['map'] ?? [],
        ]);
    }
}
