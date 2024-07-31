<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\FieldType\ImageMap;

use Ibexa\Contracts\Core\FieldType\StorageGatewayInterface;
use Ibexa\Contracts\Core\Persistence\Content\Field;
use Ibexa\Contracts\Core\Persistence\Content\VersionInfo;
use Ibexa\Core\FieldType\Image\AliasCleanerInterface;
use Ibexa\Core\FieldType\Image\ImageStorage;
use Ibexa\Core\FieldType\Image\PathGenerator;
use Ibexa\Core\IO\FilePathNormalizerInterface;
use Ibexa\Core\IO\IOServiceInterface;
use Ibexa\Core\IO\MetadataHandler;
use Onisep\IbexaImageMapBundle\FieldType\ImageMap\ImageMapStorage\Gateway\LegacyStorage as ImageMapStorageGateway;

/**
 * Converter for ImageMap field type external storage.
 */
class Storage extends ImageStorage
{
    private ImageMapStorageGateway $imageMapGateway;

    public function __construct(
        StorageGatewayInterface $gateway,
        IOServiceInterface $ioService,
        PathGenerator $pathGenerator,
        MetadataHandler $imageSizeMetadataHandler,
        AliasCleanerInterface $aliasCleaner,
        FilePathNormalizerInterface $filePathNormalizer,
        ImageMapStorageGateway $imageMapGateway
    ) {
        parent::__construct($gateway, $ioService, $pathGenerator, $imageSizeMetadataHandler, $aliasCleaner, $filePathNormalizer);

        $this->imageMapGateway = $imageMapGateway;
    }

    public function storeFieldData(VersionInfo $versionInfo, Field $field, array $context)
    {
        if (isset($field->value->data['fileName']) || isset($field->value->externalData['fileName'])) {
            parent::storeFieldData($versionInfo, $field, $context);
        }

        if (isset($field->value->data['map'])) {
            $this->imageMapGateway->saveMap($versionInfo, $field);
        }

        return true;
    }

    public function getFieldData(VersionInfo $versionInfo, Field $field, array $context)
    {
        parent::getFieldData($versionInfo, $field, $context);

        $map = $this->imageMapGateway->getMap($field->id, $versionInfo->versionNo);

        $field->value->data['map'] = $map;
    }
}
