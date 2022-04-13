<?php

namespace Padam87\MoneyBundle\Money;

use Brick\Math\BigDecimal;
use Brick\Money\Context;
use Brick\Money\Currency;
use Brick\Money\Money;
use Padam87\MoneyBundle\Money\Context\BundleContext;

/**
 * This is a stop gap solution for a common doctrine problem; embedded objects cannot be nullable.
 * See issues and PRs linked below.
 *
 * This VO acts as a proxy, which resolves into a \Brick\Money\Money object, or null
 *
 * @see https://github.com/doctrine/orm/pull/1275
 * @see https://github.com/doctrine/orm/pull/8022
 */
class NullableMoney
{
    private ?BigDecimal $amount = null;

    private ?Currency $currency = null;

    public function __construct(?Money $money = null)
    {
        $this->amount = $money ? $money->getAmount() : null;
        $this->currency = $money ? $money->getCurrency() : null;
    }

    public function __invoke(): ?Money
    {
        if ($this->amount === null || $this->currency === null) {
            return null;
        }

        return Money::of($this->amount, $this->currency, new BundleContext());
    }
}
