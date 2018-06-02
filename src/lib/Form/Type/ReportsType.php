<?php

namespace Edgar\EzCampaign\Form\Type;

use Edgar\EzCampaign\Form\Type\Field\CampaignsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportsType extends AbstractType
{
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
        return 'edgarcampaign_reports';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'method' => 'get',
                'translation_domain' => 'edgarezcampaign',
            ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'campaign',
                CampaignsType::class,
                [
                    'required' => false,
                    'placeholder' => /* @Desc("Select a Campaign") */ 'edgar.campaign.reports.campaigns.placeholder',
                    'label' => /* @Desc("Campaigns") */ 'edgar.campaign.reports.campaigns.name',
                ]
            )
            ->add('choose', SubmitType::class, [
                'label' => /* @Desc("Select") */ 'edgar.campaign.reports.choose',
            ]);
    }
}
