<?php

namespace Padam87\MoneyBundle\Entity\Traits;

use Brick\Math\BigDecimal;
use Brick\Money\Currency;
use Brick\Money\Money;
use Doctrine\ORM\Mapping as ORM;
use Padam87\MoneyBundle\Money\Context\BundleContext;

trait MoneyFromDecimalTrait
{
    abstract public function getCurrency(): Currency;

    private function getMoney(?BigDecimal $amount): ?Money
    {
        return $amount ? Money::of($amount, $this->getCurrency(), new BundleContext()) : null;
    }

    private function setMoney(?BigDecimal &$amount, ?Money $value, bool $strict = true): self
    {
        if ($strict && !$this->getCurrency()->is($value->getCurrency())) {
            throw new \LogicException(
                sprintf('Class "%s" has currency of "%s", tried to set "%s"', __CLASS__, $this->getCurrency(), $value->getCurrency())
            );
        }

        $amount = $value->getAmount();

        return $this;
    }
}
