<?php

namespace Edgar\EzCampaign\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscribeType extends AbstractType
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
        return 'edgarcampaign_campaign_subscribe';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
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
                'email',
                EmailType::class,
                ['label' => /* @Desc("Email") */ 'edgar.campaign.campaign.subscribe.title']
            )
            ->add(
                'subscribe',
                SubmitType::class,
                ['label' => /* @Desc("Subscribe") */ 'edgar.campaign.create.subscribe']
            );
    }
}
