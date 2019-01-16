<?php

namespace Padam87\MoneyBundle\DependencyInjection;

use Money\Currencies;
use Money\Currencies\CurrencyList;
use Padam87\MoneyBundle\Doctrine\Type\CurrencyType;
use Padam87\MoneyBundle\Doctrine\Type\MoneyAmountType;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class Padam87MoneyExtension extends Extension implements PrependExtensionInterface, CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $container->setParameter('padam87_money.config', $config);
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig(
            'doctrine',
            [
                'dbal' => [
                    'types' => [
                        'money_amount' => MoneyAmountType::class,
                        'currency' => CurrencyType::class,
                    ]
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('padam87_money.config');

        $container->getDefinition('doctrine.orm.default_metadata_driver')->addMethodCall(
            'addDriver',
            [
                $container->getDefinition('Padam87\MoneyBundle\Doctrine\Mapping\Driver\MoneyEmbeddedDriver'),
                'Money'
            ]
        );

        $container->setDefinition(
            CurrencyList::class,
            new Definition(
                CurrencyList::class,
                [
                    array_fill_keys($config['currencies'], $config['scale'])
                ]
            )
        );

        $container->setAlias(Currencies::class, CurrencyList::class);
    }
}

