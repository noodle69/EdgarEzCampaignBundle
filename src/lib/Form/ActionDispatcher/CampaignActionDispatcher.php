<?php

namespace Edgar\EzCampaign\Form\ActionDispatcher;

use EzSystems\RepositoryForms\Form\ActionDispatcher\AbstractActionDispatcher;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignActionDispatcher extends AbstractActionDispatcher
{
    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @return string
     */
    protected function getActionEventBaseName()
    {
        return 'edgarcampaign_campaign';
    }
}
