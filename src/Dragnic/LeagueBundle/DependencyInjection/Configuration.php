<?php
namespace Dragnic\LeagueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root(DragnicLeagueExtension::EXT_KEY);

        $rootNode->children()
            ->scalarNode('rest_base_url')->isRequired()->end()
            ->scalarNode('api_route_prefix')->defaultValue('league_api_')->end()
            ->scalarNode('rest_api_key')->isRequired()->end()
        ->end();

        return $treeBuilder;
    }
}
