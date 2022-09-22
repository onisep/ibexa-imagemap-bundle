<?php

declare(strict_types=1);

namespace Onisep\ImageMapBundle;

use Onisep\ImageMapBundle\DependencyInjection\OnisepImageMapExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OnisepImageMapBundle extends Bundle
{
    protected $name = 'OnisepImageMapBundle';

    protected function getContainerExtensionClass()
    {
        return OnisepImageMapExtension::class;
    }
}
