<?php

namespace Padam87\MoneyBundle;

use Padam87\MoneyBundle\Money\Context\BundleContext;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Padam87MoneyBundle extends Bundle
{
    public function boot()
    {
        $config = $this->container->getParameter('padam87_money.config');

        BundleContext::setScale($config['scale']);
    }
}
