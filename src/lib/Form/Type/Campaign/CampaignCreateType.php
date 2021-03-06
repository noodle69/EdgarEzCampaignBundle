<?php

namespace Edgar\EzCampaign\Form\Type\Campaign;

use Edgar\EzCampaign\Form\Type\Field\FolderType;
use Edgar\EzCampaign\Form\Type\Field\ListType;
use Edgar\EzCampaign\Values\Core\Campaign;
use Edgar\EzUISites\Form\Constraints\SiteConstraint;
use Edgar\EzUISites\Form\Type\FilterSitesType;
use EzSystems\EzPlatformAdminUi\Form\Type\UniversalDiscoveryWidget\UniversalDiscoveryWidgetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignCreateType extends AbstractType
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
        return 'edgarcampaign_campaign_create';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Campaign::class,
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
                'title',
                TextType::class,
                ['label' => /* @Desc("Title") */ 'edgar.campaign.campaign.create.title']
            )
            ->add(
                'list_id',
                ListType::class,
                [
                    'label' => /* @Desc("List") */ 'edgar.campaign.campaign.create.list_id',
                ]
            )
            ->add(
                'folder_id',
                FolderType::class,
                [
                    'label' => /* @Desc("Folder") */ 'edgar.campaign.campaign.create.folder_id',
                ]
            )
            ->add(
                'content',
                UniversalDiscoveryWidgetType::class,
                [
                    'required' => false,
                    'label' => /* @Desc("Select a content") */ 'edgar.campaign.campaign.create.content',
                ]
            )
            ->add(
                'site',
                FilterSitesType::class,
                [
                    'label' => /* @Desc("Site") */ 'edgar.campaign.campaign.create.site',
                    'required' => false,
                    'placeholder' => /* @Desc("Select a site") */ 'edgar.campaign.campaign.create.site.placeholder',
                    'multiple' => false,
                    'expanded' => false,
                    'constraints' => [new SiteConstraint()],
                ]
            )
            ->add(
                'subject_line',
                TextType::class,
                ['label' => /* @Desc("Subject") */ 'edgar.campaign.campaign.create.subject_line']
            )
            ->add(
                'from_name',
                TextType::class,
                ['label' => /* @Desc("From name") */ 'edgar.campaign.campaign.create.from_name']
            )
            ->add(
                'reply_to',
                EmailType::class,
                ['label' => /* @Desc("Reply to") */ 'edgar.campaign.campaign.create.reply_to']
            )
            ->add(
                'save',
                SubmitType::class,
                ['label' => /* @Desc("Create") */ 'edgar.campaign.create.save']
            );
    }
}
