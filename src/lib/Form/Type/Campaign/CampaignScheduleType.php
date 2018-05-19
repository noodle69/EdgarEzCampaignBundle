<?php

namespace Edgar\EzCampaign\Form\Type\Campaign;

use EzSystems\RepositoryForms\Form\Type\FieldType\DateTimeFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignScheduleType extends AbstractType
{
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'edgarcampaign_campaign_schedule';
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
                'schedule_time',
                DateTimeFieldType::class,
                ['label' => /** @Desc("Schedule time") */ 'edgar.campaign.schedule.schedule_time']
            )
            ->add(
                'schedule',
                SubmitType::class,
                ['label' => /** @Desc("Schedule") */ 'edgar.campaign.schedule.schedule']
            );
    }
}
