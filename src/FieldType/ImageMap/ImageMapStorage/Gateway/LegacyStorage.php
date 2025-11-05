<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\FieldType\ImageMap\ImageMapStorage\Gateway;

use Ibexa\Contracts\Core\Persistence\Content\Field;
use Ibexa\Contracts\Core\Persistence\Content\VersionInfo;
use Onisep\IbexaImageMapBundle\Database\ImageMapRepository;

class LegacyStorage
{
    public function __construct(private readonly ImageMapRepository $imageMapRepository)
    {
    }

    public function getMap(int $fieldId, int $version): ?array
    {
        return $this->imageMapRepository->get($fieldId, $version);
    }

    public function saveMap(VersionInfo $versionInfo, Field $field): void
    {
        $fieldId = $field->id;
        $version = $versionInfo->versionNo;

        $exists = $this->imageMapRepository->get($fieldId, $version);

        if ($exists) {
            $this->imageMapRepository->update($fieldId, $version, $field->value->data['map']);

            return;
        }

        $this->imageMapRepository->create($fieldId, $version, $field->value->data['map']);
    }
}
