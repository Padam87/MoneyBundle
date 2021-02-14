<?php

namespace Padam87\MoneyBundle\Exchange;

use Decimal\Decimal;
use Doctrine\Persistence\ManagerRegistry;
use Money\Currency;
use Money\CurrencyPair;
use App\Entity\ExchangeRate;
use Money\Exception\UnresolvableCurrencyPairException;
use Padam87\MoneyBundle\Entity\ExchangeRateInterface;
use Padam87\MoneyBundle\Repository\ExchangeRateRepositoryInterface;

class DatabaseExchange implements \Money\Exchange
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritdoc}
     */
    public function quote(Currency $baseCurrency, Currency $counterCurrency)
    {
        if ($baseCurrency->getCode() === $counterCurrency->getCode()) {
            return new CurrencyPair($baseCurrency, $counterCurrency, 1);
        }

        $repo = $this->doctrine->getRepository(ExchangeRateInterface::class);

        if (!$repo instanceof ExchangeRateRepositoryInterface) {
            throw new \LogicException(
                sprintf('"%s" must implement %s', $repo->getClassName(), ExchangeRateRepositoryInterface::class)
            );
        }

        if (null === $exchangeRate = $repo->getExchangeRate($baseCurrency, $counterCurrency)) {
            throw new UnresolvableCurrencyPairException(
                sprintf('%s - >%s ratio not found in the database.', $baseCurrency->getCode(), $counterCurrency->getCode())
            );
        }

        return $exchangeRate->getCurrencyPair();
    }
}
