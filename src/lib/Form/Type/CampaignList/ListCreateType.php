<?php

namespace Edgar\EzCampaign\Form\Type\CampaignList;

use Edgar\EzCampaign\Form\Type\Field\LanguageType;
use EzSystems\RepositoryForms\Form\Type\FieldType\CountryFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListCreateType extends AbstractType
{
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'edgarcampaign_list_create';
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
                'name',
                TextType::class,
                ['label' => /** @Desc("Name") */ 'edgar.campaign.list.create.name']
            )
            ->add(
                'company',
                TextType::class,
                ['label' => /** @Desc("Company") */ 'edgar.campaign.list.create.company']
            )
            ->add(
                'address',
                TextType::class,
                ['label' => /** @Desc("Address") */ 'edgar.campaign.list.create.address']
            )
            ->add(
                'address2',
                TextType::class,
                [
                    'required' => false,
                    'label' => /** @Desc("Additionnal address") */ 'edgar.campaign.list.create.address2'
                ]
            )
            ->add(
                'city',
                TextType::class,
                ['label' => /** @Desc("City") */ 'edgar.campaign.list.create.city']
            )
            ->add(
                'state',
                TextType::class,
                ['label' => /** @Desc("State") */ 'edgar.campaign.list.create.state']
            )
            ->add(
                'zip',
                TextType::class,
                ['label' => /** @Desc("Zip") */ 'edgar.campaign.list.create.zip']
            )
            ->add(
                'country',
                CountryFieldType::class,
                ['label' => /** @Desc("Country") */ 'edgar.campaign.list.create.country']
            )
            ->add(
                'phone',
                TextType::class,
                [
                    'required' => false,
                    'label' => /** @Desc("Phone") */ 'edgar.campaign.list.create.phone'
                ]
            )
            ->add(
                'permission_reminder',
                TextType::class,
                ['label' => /** @Desc("Permission reminder") */ 'edgar.campaign.list.create.permission_reminder']
            )
            ->add(
                'use_archive_bar',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => /** @Desc("Use archive bar?") */ 'edgar.campaign.list.create.use_archive_bar'
                ]
            )
            ->add(
                'from_name',
                TextType::class,
                ['label' => /** @Desc("From name") */ 'edgar.campaign.list.create.from_name']
            )
            ->add(
                'from_email',
                EmailType::class,
                ['label' => /** @Desc("From email") */ 'edgar.campaign.list.create.from_email']
            )
            ->add(
                'subject',
                TextType::class,
                ['label' => /** @Desc("Subject") */ 'edgar.campaign.list.create.subject']
            )
            ->add(
                'language',
                LanguageType::class,
                ['label' => /** @Desc("Language") */ 'edgar.campaign.list.create.language']
            )
            ->add(
                'notify_on_subscribe',
                EmailType::class,
                [
                    'required' => false,
                    'label' => /** @Desc("Notify on subscribe") */ 'edgar.campaign.list.create.notify_on_subscribe'
                ]
            )
            ->add(
                'notify_on_unsubscribe',
                EmailType::class,
                [
                    'required' => false,
                    'label' => /** @Desc("Notify on unsubscribe") */ 'edgar.campaign.list.create.notify_on_unsubscribe'
                ]
            )
            ->add(
                'visibility',
                ChoiceType::class,
                [
                    'choices'  => [
                        'Public' => 'pub',
                        'Private' => 'prv',
                    ],
                    'label' => /** @Desc("Visibility") */ 'edgar.campaign.list.create.visibility'
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                ['label' => /** @Desc("Create") */ 'edgar.campaign.list.create.save']
            );
    }
}
