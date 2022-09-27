<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\Twig;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use Twig\Extension\RuntimeExtensionInterface;

class ImageMapRuntime implements RuntimeExtensionInterface
{
    private LocationService $locationService;
    private ContentService $contentService;

    public function __construct(LocationService $locationService, ContentService $contentService)
    {
        $this->locationService = $locationService;
        $this->contentService = $contentService;
    }

    public function loadImageMapItems(Field $field): array
    {
        $imageMapItems = [];
        foreach ($field->value->map as $imageMapItem) {
            $map = $imageMapItem + [
                'target' => '',
                'shape' => '',
                'position' => '',
                'description' => '',
                'anchor' => '',
            ];

            if (!preg_match('~^ezobject://~', $map['link'] ?? '')) {
                $imageMapItems[] = $map;

                continue;
            }

            $linkContentId = (int) substr($map['link'], 11);
            try {
                $linkLocation = $this->locationService->loadLocation(
                    $this->contentService->loadContentInfo($linkContentId)->mainLocationId
                );
                if ($linkLocation->invisible) {
                    continue;
                }

                $map['content'] = $this->contentService->loadContent($linkContentId);
                $map['link'] = '#';
            } catch (NotFoundException $e) {
                continue;
            }

            $imageMapItems[] = $map;
        }

        return $imageMapItems;
    }
}
