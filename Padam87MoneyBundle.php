<?php

namespace Padam87\MoneyBundle;

use Padam87\MoneyBundle\Doctrine\Type\MoneyAmountType;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Padam87MoneyBundle extends Bundle
{
    public function boot()
    {
        $config = $this->container->getParameter('padam87_money.config');

        MoneyAmountType::$precision = $config['precision'];
        MoneyAmountType::$scale = $config['scale'];
    }
}
