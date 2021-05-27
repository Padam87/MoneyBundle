<?php

namespace Padam87\MoneyBundle\Repository;

use Padam87\MoneyBundle\Entity\ExchangeRateInterface;

interface ExchangeRateRepositoryInterface
{
    public function getExchangeRate(string $sourceCurrencyCode, string $targetCurrencyCode): ?ExchangeRateInterface;
}
