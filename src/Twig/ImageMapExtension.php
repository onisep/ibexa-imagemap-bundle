<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ImageMapExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('load_image_map_items', [ImageMapRuntime::class, 'loadImageMapItems']),
        ];
    }
}
