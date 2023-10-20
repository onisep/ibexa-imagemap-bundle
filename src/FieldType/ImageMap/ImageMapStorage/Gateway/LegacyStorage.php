<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\FieldType\ImageMap\ImageMapStorage\Gateway;

use Ibexa\Contracts\Core\Persistence\Content\Field;
use Ibexa\Contracts\Core\Persistence\Content\VersionInfo;
use Onisep\IbexaImageMapBundle\Database\ImageMapRepository;

class LegacyStorage
{
    private ImageMapRepository $repository;

    public function __construct(ImageMapRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getMap(int $fieldId, int $version): ?array
    {
        return $this->repository->get($fieldId, $version);
    }

    public function saveMap(VersionInfo $versionInfo, Field $field): void
    {
        $fieldId = $field->id;
        $version = $versionInfo->versionNo;

        $exists = $this->repository->get($fieldId, $version);

        if ($exists) {
            $this->repository->update($fieldId, $version, $field->value->data['map']);

            return;
        }

        $this->repository->create($fieldId, $version, $field->value->data['map']);
    }
}
