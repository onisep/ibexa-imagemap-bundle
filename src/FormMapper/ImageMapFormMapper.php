<?php

declare(strict_types=1);

namespace Onisep\IbexaImageMapBundle\FormMapper;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\FieldTypeService;
use eZ\Publish\API\Repository\LocationService;
use EzSystems\EzPlatformAdminUi\FieldType\Mapper\AbstractRelationFormMapper;
use EzSystems\EzPlatformAdminUi\Form\Data\FieldDefinitionData;
use EzSystems\EzPlatformContentForms\Data\Content\FieldData;
use EzSystems\EzPlatformContentForms\FieldType\FieldValueFormMapperInterface;
use Onisep\IbexaImageMapBundle\FieldType\ImageMap\Value;
use Onisep\IbexaImageMapBundle\Form\ImageMapType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;

class ImageMapFormMapper extends AbstractRelationFormMapper implements FieldValueFormMapperInterface
{
    private FieldTypeService $fieldTypeService;

    public function __construct(
        ContentTypeService $contentTypeService,
        LocationService $locationService,
        FieldTypeService $fieldTypeService
    ) {
        parent::__construct($contentTypeService, $locationService);

        $this->fieldTypeService = $fieldTypeService;
    }

    public function mapFieldDefinitionForm(FormInterface $fieldDefinitionForm, FieldDefinitionData $data): void
    {
        $isTranslation = $data->contentTypeData->languageCode !== $data->contentTypeData->mainLanguageCode;
        $fieldDefinitionForm
            ->add('selectionContentTypes', ChoiceType::class, [
                'choices' => $this->getContentTypesHash(),
                'expanded' => false,
                'multiple' => true,
                'required' => false,
                'property_path' => 'fieldSettings[selectionContentTypes]',
                'label' => 'field_definition.ezobjectrelationlist.selection_content_types',
                'disabled' => $isTranslation,
            ])
        ;
    }

    public function mapFieldValueForm(FormInterface $fieldForm, FieldData $data)
    {
        $fieldDefinition = $data->fieldDefinition;
        $formConfig = $fieldForm->getConfig();
        $fieldType = $this->fieldTypeService->getFieldType($fieldDefinition->fieldTypeIdentifier);

        $fieldForm
            ->add(
                $formConfig->getFormFactory()->createBuilder()
                    ->create(
                        'value',
                        ImageMapType::class,
                        [
                            'required' => $fieldDefinition->isRequired,
                            'label' => $fieldDefinition->getName(),
                            'is_alternative_text_required' => false,
                        ]
                    )
                    ->addModelTransformer(new ImageMapTransformer($fieldType, $data->value, Value::class))
                    ->setAutoInitialize(false)
                    ->getForm()
            );
    }
}
