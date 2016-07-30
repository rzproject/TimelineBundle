<?php

namespace Rz\TimelineBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('rz_timeline');

        $rootNode
            ->children()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('timeline')->defaultValue('App\\TimelineBundle\\Entity\\Timeline')->end()
                        ->scalarNode('action')->defaultValue('App\\TimelineBundle\\Entity\\Action')->end()
                        ->scalarNode('action_component')->defaultValue('App\\TimelineBundle\\Entity\\ActionComponent')->end()
                        ->scalarNode('component')->defaultValue('App\\TimelineBundle\\Entity\\Component')->end()
                    ->end()
                ->end()
                ->arrayNode('manager_class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('orm')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('timeline')->defaultValue('Rz\\TimelineBundle\\Entity\\TimelineManager')->end()
                                ->scalarNode('action')->defaultValue('Rz\\TimelineBundle\\Entity\\ActionManager')->end()
                                ->scalarNode('action_component')->defaultValue('Rz\\TimelineBundle\\Entity\\ActionComponentManger')->end()
                                ->scalarNode('component')->defaultValue('Rz\\TimelineBundle\\Entity\\ComponentManager')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('admin')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('timeline')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\TimelineBundle\\Admin\\TimelineAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('RzTimelineBundle:TimelineAdmin')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('SonataTimelineBundle')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('block')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('timeline')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\TimelineBundle\\Block\\TimelineBlockService')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
