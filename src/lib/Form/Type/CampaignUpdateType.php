<?php

namespace Edgar\EzCampaign\Form\Type;

use Edgar\EzCampaign\Data\CampaignUpdateData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignUpdateType extends AbstractType
{
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'edgarcampaign_campaign_edit';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => CampaignUpdateData::class,
                'translation_domain' => 'edgarezcampaign',
            ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                ['label' => /** @Desc("Title") */ 'edgar.campaign.campaign.create.title']
            )
            ->add(
                'list_id',
                \Edgar\EzCampaign\Form\Type\Field\ListType::class,
                ['label' => /** @Desc("List") */ 'edgar.campaign.campaign.create.list_id']
            )
            ->add(
                'subject_line',
                TextType::class,
                ['label' => /** @Desc("Subject") */ 'edgar.campaign.campaign.create.subject_line']
            )
            ->add(
                'from_name',
                TextType::class,
                ['label' => /** @Desc("From name") */ 'edgar.campaign.campaign.create.from_name']
            )
            ->add(
                'reply_to',
                TextType::class,
                ['label' => /** @Desc("Reply to") */ 'edgar.campaign.campaign.create.reply_to']
            )
            ->add(
                'folder_id',
                \Edgar\EzCampaign\Form\Type\Field\FolderType::class,
                ['label' => /** @Desc("Folder") */ 'edgar.campaign.campaign.create.folder_id']
            )
            ->add(
                'save',
                SubmitType::class,
                ['label' => /** @Desc("Create") */ 'edgar.campaign.create.save']
            );
    }
}
