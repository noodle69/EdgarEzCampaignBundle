<?php

namespace Edgar\EzCampaign\Form\Type\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class CampaignListType extends AbstractType
{
    public function getParent()
    {
        return SearchType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'campaignlist';
    }
}
