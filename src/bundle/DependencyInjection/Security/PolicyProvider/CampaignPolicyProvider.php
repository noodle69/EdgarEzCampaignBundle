<?php

namespace Edgar\EzCampaignBundle\DependencyInjection\Security\PolicyProvider;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Security\PolicyProvider\YamlPolicyProvider;

class CampaignPolicyProvider extends YamlPolicyProvider
{
    /** @var string $path bundle path */
    protected $path;

    /**
     * CampaignPolicyProvider constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return [$this->path . '/Resources/config/policies.yml'];
    }
}
