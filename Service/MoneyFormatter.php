<?php

namespace Padam87\MoneyBundle\Service;

use Brick\Money\Currency;
use Brick\Money\Money;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Currencies;

class MoneyFormatter
{
    private RequestStack $requestStack;
    private string $defaultLocale;
    private array $config;

    public function __construct(RequestStack $requestStack, string $defaultLocale, array $config)
    {
        $this->requestStack = $requestStack;
        $this->defaultLocale = $defaultLocale;
        $this->config = $config;
    }

    public function format(?Money $money, ?int $digits = null): ?string
    {
        if ($money === null) {
            return null;
        }

        if ($digits === null) {
            $digits = $this->getCurrencyDigits($money->getCurrency());
        }

        return $money->formatWith($this->createFormatter($digits));
    }

    public function amount(?Money $money, ?int $digits = null): ?string
    {
        if ($money === null) {
            return null;
        }

        if ($digits === null) {
            $digits = $this->getCurrencyDigits($money->getCurrency());
        }

        return $this->createFormatter($digits, \NumberFormatter::DECIMAL)->format((string) $money->getAmount());
    }

    public function currency(?Money $money): ?string
    {
        if ($money === null) {
            return null;
        }

        return Currencies::getSymbol($money->getCurrency()->getCurrencyCode());
    }

    private function createFormatter(?int $digits = null, int $formatStyle = \NumberFormatter::CURRENCY): \NumberFormatter
    {
        $locale = $this->getLocale();
        $formatter = new \NumberFormatter($locale, $formatStyle);
        $formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_CEILING);

        if ($digits !== null) {
            $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $digits);
            $formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $digits);
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $digits);
            $formatter->setAttribute(\NumberFormatter::DECIMAL_ALWAYS_SHOWN, $digits);
        }

        return $formatter;
    }

    private function getCurrencyDigits(Currency $currency): ?int
    {
        if (array_key_exists($currency->getCurrencyCode(), $this->config['currency_digits'])) {
            return (int) $this->config['currency_digits'][$currency->getCurrencyCode()];
        }

        return null;
    }

    private function getLocale()
    {
        if (null === $request = $this->requestStack->getMainRequest()) {
            return $this->defaultLocale;
        }

        return $request->getLocale();
    }
}
