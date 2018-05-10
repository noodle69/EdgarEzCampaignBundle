<?php

namespace Edgar\EzCampaign\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                $builder->create('recipients', 'form', array('virtual' => true))
                    ->add('list_id', \Edgar\EzCampaign\Form\Type\Field\ListType::class, ['required' => true, 'label' => 'campaign.campaign.list'])
            )
            ->add(
                $builder->create('settings', 'form', array('virtual' => true))
                    ->add('subject_line', TextType::class, ['required' => true, 'label' => 'campaign.campaign.subject_line'])
                    ->add('title', TextType::class, ['required' => true, 'label' => 'campaign.campaign.title'])
                    ->add('from_name', TextType::class, ['required' => true, 'label' => 'campaign.campaign.from_name'])
                    ->add('reply_to', EmailType::class, ['required' => true, 'label' => 'campaign.campaign.reply_to'])
                    ->add('folder_id', \Edgar\EzCampaign\Form\Type\Field\FolderType::class, ['required' => true, 'label' => 'campaign.campaign.folder_id'])
            )
            ->add('save', SubmitType::class, ['label' => 'campaign.save']);
    }

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
        $resolver->setDefaults([
            'data_class' => '\Edgar\EzCampaign\Values\CampaignStruct',
            'translation_domain' => 'edgarezcampaign',
        ]);
    }
}
