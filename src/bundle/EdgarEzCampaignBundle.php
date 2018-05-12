<?php

namespace Edgar\EzCampaignBundle;

use Edgar\EzCampaignBundle\DependencyInjection\Configuration\Pagination;
use Edgar\EzCampaignBundle\DependencyInjection\Security\PolicyProvider\CampaignPolicyProvider;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EdgarEzCampaignBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        /** @var EzPublishCoreExtension $eZExtension */
        $eZExtension = $container->getExtension('ezpublish');
        $eZExtension->addPolicyProvider(new CampaignPolicyProvider($this->getPath()));
    }
}
