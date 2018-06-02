<?php

namespace Edgar\EzCampaign\FieldType\Mapper;

use Edgar\EzCampaign\Form\Type\FieldType\CampaignFieldType;
use EzSystems\RepositoryForms\Data\Content\FieldData;
use EzSystems\RepositoryForms\Data\FieldDefinitionData;
use EzSystems\RepositoryForms\FieldType\FieldDefinitionFormMapperInterface;
use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignFormMapper implements FieldDefinitionFormMapperInterface, FieldValueFormMapperInterface
{
    /**
     * @param FormInterface $fieldDefinitionForm
     * @param FieldDefinitionData $data
     */
    public function mapFieldDefinitionForm(FormInterface $fieldDefinitionForm, FieldDefinitionData $data)
    {
        $fieldDefinitionForm
            ->add(
                $fieldDefinitionForm
                    ->getConfig()->getFormFactory()->createBuilder()
                    ->create('defaultValue', CampaignFieldType::class, [
                        'required' => false,
                        'label' => 'field_definition.ezstring.default_value',
                    ])
                    ->setAutoInitialize(false)
                    ->getForm()
            );
    }

    /**
     * @param FormInterface $fieldForm
     * @param FieldData $data
     */
    public function mapFieldValueForm(FormInterface $fieldForm, FieldData $data)
    {
        $fieldDefinition = $data->fieldDefinition;
        $formConfig = $fieldForm->getConfig();
        $names = $fieldDefinition->getNames();
        $label = $fieldDefinition->getName($formConfig->getOption('mainLanguageCode')) ?: reset($names);

        $fieldForm
            ->add(
                $formConfig->getFormFactory()->createBuilder()
                    ->create(
                        'value',
                        CampaignFieldType::class,
                        [
                            'required' => $fieldDefinition->isRequired,
                            'label' => $label,
                        ]
                    )
                    ->setAutoInitialize(false)
                    ->getForm()
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'translation_domain' => 'ezrepoforms_content_type',
            ]);
    }
}
