<?php

namespace Padam87\MoneyBundle\Repository;

use Money\Currency;
use Padam87\MoneyBundle\Entity\ExchangeRateInterface;

interface ExchangeRateRepositoryInterface
{
    public function getExchangeRate(Currency $baseCurrency, Currency $counterCurrency): ?ExchangeRateInterface;
}
