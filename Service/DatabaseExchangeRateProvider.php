<?php

namespace Padam87\MoneyBundle\Service;

use Brick\Money\Exception\CurrencyConversionException;
use Brick\Money\ExchangeRateProvider;
use Doctrine\Persistence\ManagerRegistry;
use Padam87\MoneyBundle\Entity\ExchangeRateInterface;
use Padam87\MoneyBundle\Repository\ExchangeRateRepositoryInterface;

class DatabaseExchangeRateProvider implements ExchangeRateProvider
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getExchangeRate(string $sourceCurrencyCode, string $targetCurrencyCode)
    {
        if ($sourceCurrencyCode === $targetCurrencyCode) {
            return 1;
        }

        $repo = $this->doctrine->getRepository(ExchangeRateInterface::class);

        if (!$repo instanceof ExchangeRateRepositoryInterface) {
            throw new \LogicException(
                sprintf('"%s" must implement %s', $repo->getClassName(), ExchangeRateRepositoryInterface::class)
            );
        }

        if (null === $exchangeRate = $repo->getExchangeRate($sourceCurrencyCode, $targetCurrencyCode)) {
            throw CurrencyConversionException::exchangeRateNotAvailable($sourceCurrencyCode, $targetCurrencyCode);
        }

        return $exchangeRate->getConversionRatio();
    }
}
