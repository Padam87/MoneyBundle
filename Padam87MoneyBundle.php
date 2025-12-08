<?php

namespace Padam87\MoneyBundle;

use Doctrine\DBAL\Types\Type;
use Padam87\MoneyBundle\Doctrine\Type\CurrencyType;
use Padam87\MoneyBundle\Doctrine\Type\DecimalObjectType;
use Padam87\MoneyBundle\Money\Context\BundleContext;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Padam87MoneyBundle extends Bundle
{
    public function boot(): void
    {
        $config = $this->container->getParameter('padam87_money.config');

        BundleContext::setScale($config['scale']);

        // @TODO: Keep an eye on https://github.com/doctrine/DoctrineBundle/issues/1867 for a better way to do this.
        if (!Type::hasType('decimal_object')) {
            Type::addType('decimal_object', new DecimalObjectType($config));
        }
        
        if (!Type::hasType('currency')) {
            Type::addType('currency', CurrencyType::class);
        }
    }
}
