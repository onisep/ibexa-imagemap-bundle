<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\DataTransformer;

use Ibexa\ContentForms\FieldType\DataTransformer\AbstractBinaryBaseTransformer;
use Onisep\IbexaImageMapBundle\FieldType\ImageMap\Value;
use Symfony\Component\Form\DataTransformerInterface;

class ImageMapTransformer extends AbstractBinaryBaseTransformer implements DataTransformerInterface
{
    #[\Override]
    public function transform(mixed $value): array
    {
        if (null === $value) {
            $value = $this->fieldType->getEmptyValue();
        }

        return array_merge(
            $this->getDefaultProperties(),
            [
                'alternativeText' => $value->alternativeText,
                'additionalData' => $value->additionalData,
                'map' => $value->map,
            ]
        );
    }

    #[\Override]
    public function reverseTransform(mixed $value): Value
    {
        /** @var Value $valueObject */
        $valueObject = $this->getReverseTransformedValue($value);

        if (!$this->fieldType->isEmptyValue($valueObject)) {
            $valueObject->alternativeText = $value['alternativeText'];
            $valueObject->additionalData = $value['additionalData'];
        }

        $valueObject->map = $value['map'];

        return $valueObject;
    }
}
