<?php

namespace Padam87\MoneyBundle\Entity\Embeddables;

use Brick\Math\BigDecimal;
use Brick\Money\Currency;
use Brick\Money\Money;
use Doctrine\ORM\Mapping as ORM;

/**
 * This is a stop gap solution for a common doctrine problem; embedded objects cannot be nullable.
 * See issues and PRs linked below.
 *
 * This VO acts as a proxy, which resolves into a \Brick\Money\Money object, or null
 *
 * @see https://github.com/doctrine/orm/pull/1275
 * @see https://github.com/doctrine/orm/pull/8022
 *
 * @ORM\Embeddable()
 */
class NullableMoney
{
    /**
     * @ORM\Column(type="decimal_object", precision=28, scale=4, nullable=true)
     */
    private ?BigDecimal $amount = null;

    /**
     * @ORM\Column(type="currency", nullable=true)
     */
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

        return Money::of($this->amount, $this->currency);
    }
}
