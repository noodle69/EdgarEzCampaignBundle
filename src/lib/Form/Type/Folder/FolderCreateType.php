<?php

namespace Edgar\EzCampaign\Form\Type\Folder;

use Edgar\EzCampaign\Values\Core\Folder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FolderCreateType extends AbstractType
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
        return 'edgarcampaign_folder_create';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Folder::class,
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
                'name',
                TextType::class,
                ['label' => /* @Desc("Name") */ 'edgar.campaign.folder.create.name']
            )
            ->add('create', SubmitType::class, [
                'label' => /* @Desc("Create") */ 'edgar.campaign.folder.create',
            ]);
    }
}
