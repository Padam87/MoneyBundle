<?php

namespace Padam87\MoneyBundle\Money\Context;

use Brick\Math\BigDecimal;
use Brick\Math\BigNumber;
use Brick\Money\Context;
use Brick\Money\Currency;

class BundleContext implements Context
{
    private static int $scale;

    /**
     * @inheritdoc
     */
    public function applyTo(BigNumber $amount, Currency $currency, int $roundingMode) : BigDecimal
    {
        return $amount->toScale(self::$scale, $roundingMode);
    }

    /**
     * {@inheritdoc}
     */
    public function getStep() : int
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     */
    public function isFixedScale() : bool
    {
        return true;
    }

    public static function getScale(): int
    {
        return self::$scale;
    }

    public static function setScale($scale): void
    {
        self::$scale = $scale;
    }
}
