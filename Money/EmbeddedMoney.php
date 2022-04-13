<?php

namespace Padam87\MoneyBundle\Money;

use Brick\Math\BigDecimal;
use Brick\Money\Currency;
use Brick\Money\Money;
use Padam87\MoneyBundle\Money\Context\BundleContext;

/**
 * This VO acts as a proxy, which resolves into a \Brick\Money\Money object
 *
 * Doctrine does not allow to set a Context in any way, and lets it be null by bypassing the constructor.
 * Later on this causes varios issues in the code.
 *
 * By embedding this class this problem is avoided by providing the BundleContext.
 */
class EmbeddedMoney
{
    private BigDecimal $amount;

    private Currency $currency;

    public function __construct(Money $money)
    {
        $this->amount = $money->getAmount();
        $this->currency = $money->getCurrency();
    }

    public static function of($amount, Currency $currency): self
    {
        return new EmbeddedMoney(Money::of($amount, $currency, new BundleContext()));
    }

    public static function zero(Currency $currency): self
    {
        return new EmbeddedMoney(Money::zero($currency, new BundleContext()));
    }

    public function __invoke(): Money
    {
        return Money::of($this->amount, $this->currency, new BundleContext());
    }
}
