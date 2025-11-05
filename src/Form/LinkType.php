<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\Form;

use Ibexa\Contracts\Core\Repository\ContentService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class LinkType extends AbstractType
{
    public function __construct(private readonly ContentService $contentService)
    {
    }

    #[\Override]
    public function getParent(): ?string
    {
        return TextType::class;
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'imagemap_link';
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $content = null;
        if (preg_match('~^ezobject://~', $form->getData() ?? '')) {
            try {
                $content = $this->contentService->loadContent((int) substr((string) $form->getData(), 11));
            } catch (\Throwable) {
                // Not found, do nothing.
            }
        }

        $view->vars += [
            'content' => $content,
        ];
    }
}
