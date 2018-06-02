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

    /** @var  */
    private $campaignsData;

    /**
     * CampaignFieldType constructor.
     *
     * @param FieldTypeService $fieldTypeService
     * @param CampaignsService $campaignsService
     */
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

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'ezplatform_fieldtype_edgarcampaign';
    }

    /**
     * @return null|string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(
            new CampaignValueTransformer($this->campaignsData)
        );
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attributes = [];

        $view->vars['attr'] = array_merge($view->vars['attr'], $attributes);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'expanded' => false,
            'choices' => $this->getCampaignChoices($this->campaignsData),
            'choices_as_values' => true,
        ]);
    }

    /**
     * @param array $campaigns
     *
     * @return array
     */
    private function getCampaignChoices(array $campaigns)
    {
        $choices = [];
        foreach ($campaigns as $campaign) {
            $choices[$campaign['settings']['title']] = $campaign['id'];
        }

        return $choices;
    }
}
