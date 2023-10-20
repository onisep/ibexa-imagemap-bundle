<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\FieldType\ImageMap;

use Ibexa\Contracts\Core\Persistence\Content\Field;
use Ibexa\Contracts\Core\Persistence\Content\VersionInfo;
use Ibexa\Core\Base\Utils\DeprecationWarnerInterface as DeprecationWarner;
use Ibexa\Core\FieldType\Image\AliasCleanerInterface;
use Ibexa\Core\FieldType\Image\ImageStorage;
use Ibexa\Core\FieldType\Image\ImageStorage\Gateway as ImageStorageGateway;
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
