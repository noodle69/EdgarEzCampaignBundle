<?php

namespace Edgar\EzCampaign\Form\Type;

use Edgar\EzCampaign\Form\Type\Field\CampaignsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportsType extends AbstractType
{
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'edgarcampaign_reports';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'translation_domain' => 'edgarezcampaign',
            ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'campaign',
                CampaignsType::class,
                ['label' => /* @Desc("Campaigns") */ 'edgar.campaign.reports.campaigns.name']
            )
            ->add('choose', SubmitType::class, [
                'label' => /* @Desc("Choose") */ 'edgar.campaign.reports.choose',
            ]);
    }
}
