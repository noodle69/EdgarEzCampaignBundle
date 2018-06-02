<?php

namespace Edgar\EzCampaign\Form\Type\CampaignList;

use Edgar\EzCampaign\Values\Core\CampaignList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListDeleteType extends AbstractType
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
        return 'edgarcampaign_list_delete';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'trash',
                SubmitType::class,
                ['label' => /* @Desc("Send to Trash") */ 'campaign.list_trash_form.trash']
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CampaignList::class,
            'translation_domain' => 'edgarezcampaign',
        ]);
    }
}
