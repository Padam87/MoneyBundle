<?php

namespace Padam87\MoneyBundle\Service;

use Money\Currency;
use Money\Money;

class MoneyHelper
{
    private $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function createMoney($amount, Currency $currency): Money
    {
        return new Money(bcmul($amount, pow(10, $this->config['scale']), 0), $currency);
    }

    public function getAmount(Money $money, ?int $scale = null): string
    {
        return bcdiv(
            $money->getAmount(),
            pow(10, $this->config['scale']), $scale === null ? $this->config['scale'] : $scale
        );
    }
}
