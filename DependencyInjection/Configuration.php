<?php

namespace Padam87\MoneyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('padam87_money');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->integerNode('precision')
                    ->defaultValue(18)
                ->end()
                ->integerNode('scale')
                    ->defaultValue(2)
                ->end()
                ->scalarNode('default_currency')
                    ->defaultValue('EUR')
                ->end()
                ->arrayNode('currencies')
                    ->scalarPrototype()->end()
                    ->defaultValue(['EUR'])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
