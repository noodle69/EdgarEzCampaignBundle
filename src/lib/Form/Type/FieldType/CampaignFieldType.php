<?php

namespace Edgar\EzCampaign\Form\Type\FieldType;

use Edgar\EzCampaign\FieldType\DataTransformer\CampaignValueTransformer;
use Edgar\EzCampaignBundle\Service\CampaignsService;
use eZ\Publish\API\Repository\FieldTypeService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Welp\MailchimpBundle\Exception\MailchimpException;

class CampaignFieldType extends AbstractType
{
    /** @var FieldTypeService */
    private $fieldTypeService;

    /** @var CampaignsService */
    private $campaignsService;

    private $campaignsData;

    public function __construct(FieldTypeService $fieldTypeService, CampaignsService $campaignsService)
    {
        $this->fieldTypeService = $fieldTypeService;
        $this->campaignsService = $campaignsService;

        $this->campaignsData = [];

        try {
            $campaigns = $this->campaignsService->get(0, 0);
            if ($campaigns) {
                $this->campaignsData = $campaigns['campaigns'];
            }
        } catch (MailchimpException $e) {
        }
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'ezplatform_fieldtype_edgarcampaign';
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(
            new CampaignValueTransformer($this->campaignsData)
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attributes = [];

        $view->vars['attr'] = array_merge($view->vars['attr'], $attributes);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'expanded' => false,
            'choices' => $this->getCampaignChoices($this->campaignsData),
            'choices_as_values' => true,
        ]);
    }

    private function getCampaignChoices(array $campaigns)
    {
        $choices = [];
        foreach ($campaigns as $campaign) {
            $choices[$campaign['settings']['title']] = $campaign['id'];
        }

        return $choices;
    }
}
