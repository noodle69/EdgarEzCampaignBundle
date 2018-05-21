<?php

namespace Edgar\EzCampaign\Form\Type\Folder;

use Edgar\EzCampaign\Form\Type\Field\FoldersType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FolderFilterType extends AbstractType
{
    public function getName(): string
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix(): string
    {
        return 'edgarcampaign_filter_folders';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'method' => 'get',
                'translation_domain' => 'edgarezcampaign',
            ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'folder',
                FoldersType::class,
                [
                    'required' => false,
                    'placeholder' => /* @Desc("Select a Folder") */ 'edgar.campaign.filter.folder.placeeholder',
                    'label' => /* @Desc("Folder") */ 'edgar.campaign.filter.folder.name'
                ]
            )
            ->add('choose', SubmitType::class, [
                'label' => /* @Desc("Choose") */ 'edgar.campaign.filter.folder.choose',
            ]);
    }
}
