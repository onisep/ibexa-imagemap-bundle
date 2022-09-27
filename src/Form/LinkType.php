<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\Form;

use eZ\Publish\API\Repository\ContentService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class LinkType extends AbstractType
{
    private ContentService $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getBlockPrefix()
    {
        return 'imagemap_link';
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $content = null;
        if (preg_match('~^ezobject://~', $form->getData() ?? '')) {
            try {
                $content = $this->contentService->loadContent((int) substr($form->getData(), 11));
            } catch (\Throwable $e) {
                // Not found, do nothing.
            }
        }

        $view->vars += [
            'content' => $content,
        ];
    }
}
