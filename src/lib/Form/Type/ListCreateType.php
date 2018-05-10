<?php

namespace Edgar\EzCampaign\Form\Type;

use EzSystems\EzPlatformAdminUi\Form\Type\Language\LanguageType;
use EzSystems\RepositoryForms\Form\Type\FieldType\CountryFieldType;
use Symfony\Component\Form\AbstractType;
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
                'permission_reminder',
                TextType::class,
                ['label' => /** @Desc("Permission reminder") */ 'edgar.campaign.list.create.permission_reminder']
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
                TextType::class,
                ['label' => /** @Desc("Language") */ 'edgar.campaign.list.create.language']
            )
            ->add(
                'save',
                SubmitType::class,
                ['label' => /** @Desc("Create") */ 'edgar.campaign.list.create.save']
            );
    }
}
