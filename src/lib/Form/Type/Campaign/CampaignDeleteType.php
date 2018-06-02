<?php

namespace Edgar\EzCampaign\Form\Type\Campaign;

use Edgar\EzCampaign\Values\Core\Campaign;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignDeleteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'trash',
                SubmitType::class,
                ['label' => /* @Desc("Send to Trash") */ 'campaign.campaign_trash_form.trash']
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Campaign::class,
            'translation_domain' => 'edgarezcampaign',
        ]);
    }
}
