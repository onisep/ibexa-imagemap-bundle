<?php

declare(strict_types=1);

namespace Onisep\ImageMapBundle\FieldType\ImageMap;

use eZ\Publish\Core\Base\Utils\DeprecationWarnerInterface as DeprecationWarner;
use eZ\Publish\Core\FieldType\Image\AliasCleanerInterface;
use eZ\Publish\Core\FieldType\Image\ImageStorage;
use eZ\Publish\Core\FieldType\Image\ImageStorage\Gateway as ImageStorageGateway;
use eZ\Publish\Core\FieldType\Image\PathGenerator;
use eZ\Publish\Core\IO\FilePathNormalizerInterface;
use eZ\Publish\Core\IO\IOServiceInterface;
use eZ\Publish\Core\IO\MetadataHandler;
use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;
use Onisep\ImageMapBundle\FieldType\ImageMap\ImageMapStorage\Gateway\LegacyStorage as ImageMapStorageGateway;

/**
 * Converter for ImageMap field type external storage.
 */
class Storage extends ImageStorage
{
    private ImageMapStorageGateway $imageMapGateway;

    public function __construct(
        ImageStorageGateway $baseGateway,
        IOServiceInterface $IOService,
        PathGenerator $pathGenerator,
        MetadataHandler $imageSizeMetadataHandler,
        DeprecationWarner $deprecationWarner,
        ImageMapStorageGateway $imageMapGateway,
        AliasCleanerInterface $aliasCleaner,
        FilePathNormalizerInterface $filePathNormalizer
    ) {
        parent::__construct($baseGateway, $IOService, $pathGenerator, $imageSizeMetadataHandler, $deprecationWarner, $aliasCleaner, $filePathNormalizer);

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
