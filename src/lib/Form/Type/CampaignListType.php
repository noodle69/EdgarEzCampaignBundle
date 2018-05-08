<?php

namespace Edgar\EzCampaign\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['required' => true, 'label' => 'campaign.list.name'])
            ->add(
                $builder->create('contact', 'form', array('virtual' => true))
                    ->add('company', TextType::class, ['required' => true, 'label' => 'campaign.list.company'])
                    ->add('address', TextType::class, ['required' => true, 'label' => 'campaign.list.address'])
                    ->add('city', TextType::class, ['required' => true, 'label' => 'campaign.list.city'])
                    ->add('state', TextType::class, ['required' => true, 'label' => 'campaign.list.state'])
                    ->add('zip', TextType::class, ['required' => true, 'label' => 'campaign.list.zip'])
                    ->add('country', CountryType::class, ['required' => true, 'label' => 'campaign.list.country'])
            )
            ->add(
                $builder->create('campaign_defaults', 'form', array('virtual' => true))
                    ->add('from_name', TextType::class, ['required' => true, 'label' => 'campaign.list.from_name'])
                    ->add('from_email', EmailType::class, ['required' => true, 'label' => 'campaign.list.from_email'])
                    ->add('subject', TextType::class, ['required' => true, 'label' => 'campaign.list.subject'])
                    ->add('language', TextType::class, ['required' => true, 'label' => 'campaign.list.language'])
            )
            ->add('permission_reminder', TextareaType::class, ['required' => true, 'label' => 'campaign.list.permission_reminder'])
            ->add('save', SubmitType::class, ['label' => 'campaign.save']);
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'edgarcampaign_list_edit';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => '\Edgar\EzCampaign\Values\CampaignListStruct',
            'translation_domain' => 'edgarezcampaign',
        ]);
    }
}
