<?php

namespace Edgar\EzCampaign\Form\ActionDispatcher;

use EzSystems\RepositoryForms\Form\ActionDispatcher\AbstractActionDispatcher;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignFolderActionDispatcher extends AbstractActionDispatcher
{
    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('name');
    }

    /**
     * @return string
     */
    protected function getActionEventBaseName()
    {
        return 'edgarcampaign_folder';
    }
}
