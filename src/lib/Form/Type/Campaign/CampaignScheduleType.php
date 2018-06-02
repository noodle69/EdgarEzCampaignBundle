<?php

namespace Edgar\EzCampaign\Form\Type\Campaign;

use Edgar\EzCampaign\Values\Core\Schedule;
use EzSystems\RepositoryForms\Form\Type\FieldType\DateTimeFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignScheduleType extends AbstractType
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
        return 'edgarcampaign_campaign_schedule';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Schedule::class,
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
                'schedule_time',
                DateTimeFieldType::class,
                ['label' => /* @Desc("Schedule time") */ 'edgar.campaign.schedule.schedule_time']
            )
            ->add(
                'schedule',
                SubmitType::class,
                ['label' => /* @Desc("Schedule") */ 'edgar.campaign.schedule.schedule']
            );
    }
}
