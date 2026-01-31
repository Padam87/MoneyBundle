<?php

namespace Padam87\MoneyBundle\Twig\Extension;

use Brick\Math\BigNumber;
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
    public function __construct(private MoneyFormatter $formatter, private CurrencyConverter $converter)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('money', $this->formatter->format(...)),
            new TwigFilter('money_amount', $this->formatter->amount(...)),
            new TwigFilter('money_currency', $this->formatter->currency(...)),
            new TwigFilter('money_convert', $this->converter->convert(...)),
            new TwigFilter(
                'currency',
                function ($currency): ?string {
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

    public function getFunctions(): array
    {
        return [
            new TwigFunction('money', fn(BigNumber|int|float|string $amount, Currency|string|int $currency): Money => Money::of($amount, $currency)),
            new TwigFunction('currency', fn(string|int $code): Currency => Currency::of($code)),
        ];
    }
}
