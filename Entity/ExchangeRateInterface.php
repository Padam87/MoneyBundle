<?php

namespace Padam87\MoneyBundle\Entity;

use Money\CurrencyPair;

interface ExchangeRateInterface
{
    public function getCurrencyPair(): CurrencyPair;
}
