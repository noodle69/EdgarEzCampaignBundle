<?php

namespace Edgar\EzCampaign\Form\Type\Folder;

use Edgar\EzCampaign\Form\Type\Field\FoldersType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FolderFilterType extends AbstractType
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getBlockPrefix();
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'edgarcampaign_filter_folders';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'method' => 'get',
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
                'folder',
                FoldersType::class,
                [
                    'required' => false,
                    'placeholder' => /* @Desc("Select a Folder") */ 'edgar.campaign.filter.folder.placeeholder',
                    'label' => /* @Desc("Folder") */ 'edgar.campaign.filter.folder.name',
                ]
            )
            ->add('choose', SubmitType::class, [
                'label' => /* @Desc("Choose") */ 'edgar.campaign.filter.folder.choose',
            ]);
    }
}
