<?php

namespace Padam87\MoneyBundle\DependencyInjection;

use Padam87\MoneyBundle\Doctrine\Mapping\Driver\MoneyEmbeddedDriver;
use Padam87\MoneyBundle\Doctrine\Type\CurrencyType;
use Padam87\MoneyBundle\Doctrine\Type\DecimalObjectType;
use Padam87\MoneyBundle\Money\EmbeddedMoney;
use Padam87\MoneyBundle\Money\NullableMoney;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
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
                        'decimal_object' => DecimalObjectType::class,
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
        $driver = $container->getDefinition('doctrine.orm.default_metadata_driver');
        $driver->addMethodCall(
            'addDriver',
            [
                $container->getDefinition(MoneyEmbeddedDriver::class),
                EmbeddedMoney::class
            ]
        );
        $driver->addMethodCall(
            'addDriver',
            [
                $container->getDefinition(MoneyEmbeddedDriver::class),
                NullableMoney::class
            ]
        );
    }
}

