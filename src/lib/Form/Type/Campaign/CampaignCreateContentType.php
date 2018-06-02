<?php

namespace Edgar\EzCampaign\Form\Type\Campaign;

use Edgar\EzCampaign\Values\Core\CampaignContent;
use Edgar\EzUISites\Form\Constraints\SiteConstraint;
use Edgar\EzUISites\Form\Type\FilterSitesType;
use EzSystems\EzPlatformAdminUi\Form\Type\UniversalDiscoveryWidget\UniversalDiscoveryWidgetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignCreateContentType extends AbstractType
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
        return 'edgarcampaign_campaign_create_content';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => CampaignContent::class,
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
                'content',
                UniversalDiscoveryWidgetType::class,
                [
                    'label' => /* @Desc("Select a content") */ 'edgar.campaign.campaign.create.content',
                ]
            )
            ->add(
                'site',
                FilterSitesType::class,
                [
                    'label' => /* @Desc("Site") */ 'edgar.campaign.campaign.create.site',
                    'placeholder' => /* @Desc("Select a site") */ 'edgar.campaign.campaign.create.site.placeholder',
                    'multiple' => false,
                    'expanded' => false,
                    'constraints' => [new SiteConstraint()],
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                ['label' => /* @Desc("Create") */ 'edgar.campaign.create.save']
            );
    }
}
