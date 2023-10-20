<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddDefaultViewTemplatePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $parameter = $container->getParameter('ibexasettings.default.content_view_defaults');
        $parameter['imagemap_embed'] = [
            'default' => [
                'template' => '@ezdesign/default/content/imagemap_embed.html.twig',
                'match' => [],
            ],
        ];
        $parameter['imagemap_popin'] = [
            'default' => [
                'template' => '@ezdesign/default/content/imagemap_popin.html.twig',
                'match' => [],
            ],
        ];

        $container->setParameter('ibexasettings.default.content_view_defaults', $parameter);
    }
}
