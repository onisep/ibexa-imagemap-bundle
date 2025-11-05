<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\FieldType\ImageMap;

use Ibexa\Contracts\Core\Persistence\Content\Field;
use Ibexa\Contracts\Core\Persistence\Content\VersionInfo;
use Ibexa\Core\FieldType\Image\AliasCleanerInterface;
use Ibexa\Core\FieldType\Image\ImageStorage;
use Ibexa\Core\FieldType\Image\ImageStorage\Gateway as ImageStorageGateway;
use Ibexa\Core\FieldType\Image\PathGenerator;
use Ibexa\Core\FieldType\Validator\FileExtensionBlackListValidator;
use Ibexa\Core\IO\FilePathNormalizerInterface;
use Ibexa\Core\IO\IOServiceInterface;
use Onisep\IbexaImageMapBundle\FieldType\ImageMap\ImageMapStorage\Gateway\LegacyStorage as ImageMapStorageGateway;

/**
 * Converter for ImageMap field type external storage.
 */
class Storage extends ImageStorage
{
    public function __construct(
        ImageStorageGateway $baseGateway,
        IOServiceInterface $ioService,
        PathGenerator $pathGenerator,
        AliasCleanerInterface $aliasCleaner,
        FilePathNormalizerInterface $filePathNormalizer,
        FileExtensionBlackListValidator $fileExtensionBlackListValidator,
        private readonly ImageMapStorageGateway $imageMapStorageGateway,
    ) {
        parent::__construct($baseGateway, $ioService, $pathGenerator, $aliasCleaner, $filePathNormalizer, $fileExtensionBlackListValidator);
    }

    #[\Override]
    public function storeFieldData(VersionInfo $versionInfo, Field $field): bool
    {
        if (isset($field->value->data['fileName']) || isset($field->value->externalData['fileName'])) {
            parent::storeFieldData($versionInfo, $field);
        }

        if (isset($field->value->data['map'])) {
            $this->imageMapStorageGateway->saveMap($versionInfo, $field);
        }

        return true;
    }

    #[\Override]
    public function getFieldData(VersionInfo $versionInfo, Field $field): void
    {
        parent::getFieldData($versionInfo, $field);

        $map = $this->imageMapStorageGateway->getMap($field->id, $versionInfo->versionNo);

        $field->value->data['map'] = $map;
    }
}
