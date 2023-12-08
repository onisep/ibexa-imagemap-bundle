<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddDefaultViewTemplatePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $parameter = $container->getParameter('ibexa.site_access.config.default.content_view_defaults');
        $parameter['imagemap_embed'] = [
            'default' => [
                'template' => '@ibexadesign/default/content/imagemap_embed.html.twig',
                'match' => [],
            ],
        ];
        $parameter['imagemap_popin'] = [
            'default' => [
                'template' => '@ibexadesign/default/content/imagemap_popin.html.twig',
                'match' => [],
            ],
        ];

        $container->setParameter('ibexa.site_access.config.default.content_view_defaults', $parameter);
    }
}
