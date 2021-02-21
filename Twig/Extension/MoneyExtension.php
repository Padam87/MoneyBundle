<?php

namespace Padam87\MoneyBundle\Twig\Extension;

use Brick\Money\Currency;
use Brick\Money\CurrencyConverter;
use Brick\Money\Money;
use Padam87\MoneyBundle\Service\MoneyFormatter;
use Symfony\Component\Intl\Currencies;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MoneyExtension extends AbstractExtension
{
    private MoneyFormatter $formatter;
    private CurrencyConverter $converter;

    public function __construct(MoneyFormatter $formatter, CurrencyConverter $converter)
    {
        $this->formatter = $formatter;
        $this->converter = $converter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('money', [$this->formatter, 'format']),
            new TwigFilter('money_amount', [$this->formatter, 'amount']),
            new TwigFilter('money_currency', [$this->formatter, 'currency']),
            new TwigFilter('money_convert', [$this->converter, 'convert']),
            new TwigFilter(
                'currency',
                function ($currency) {
                    if ($currency === null) {
                        return null;
                    }

                    if (!$currency instanceof Currency) {
                        $currency = Currency::of($currency);
                    }

                    return Currencies::getSymbol($currency->getCurrencyCode());
                }
            ),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('money', function ($amount, $currency) {
                return Money::of($amount, $currency);
            }),
            new TwigFunction('currency', function ($code) {
                return Currency::of($code);
            }),
        ];
    }
}
