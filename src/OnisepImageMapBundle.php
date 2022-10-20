<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle;

use Onisep\IbexaImageMapBundle\DependencyInjection\Compiler\AddDefaultViewTemplatePass;
use Onisep\IbexaImageMapBundle\DependencyInjection\OnisepImageMapExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OnisepImageMapBundle extends Bundle
{
    protected $name = 'OnisepImageMapBundle';

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddDefaultViewTemplatePass());
    }

    protected function getContainerExtensionClass(): string
    {
        return OnisepImageMapExtension::class;
    }
}
