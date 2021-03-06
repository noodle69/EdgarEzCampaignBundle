<?php

namespace Edgar\EzCampaignBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;

class Configuration extends SiteAccessConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('edgar_ez_campaign');
        $systemNode = $this->generateScopeBaseNode($rootNode);
        $systemNode
            ->scalarNode('campaign_view')
                ->info('content_view type used to anage Campaign content view')
            ->end()
            ->arrayNode('pagination')
                ->info('System pagination configuration')
                ->children()
                    ->scalarNode('campaign_limit')->isRequired()->end()
                    ->scalarNode('list_limit')->isRequired()->end()
                    ->scalarNode('report_limit')->isRequired()->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
