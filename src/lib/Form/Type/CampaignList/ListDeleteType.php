<?php

namespace Edgar\EzCampaign\Form\Type\CampaignList;

use Edgar\EzCampaign\Values\Core\CampaignList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListDeleteType extends AbstractType
{
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'edgarcampaign_list_delete';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'trash',
                SubmitType::class,
                ['label' => /** @Desc("Send to Trash") */ 'campaign.list_trash_form.trash']
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CampaignList::class,
            'translation_domain' => 'edgarezcampaign',
        ]);
    }
}
