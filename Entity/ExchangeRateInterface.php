<?php

namespace Padam87\MoneyBundle\Entity;

use Brick\Math\BigNumber;

interface ExchangeRateInterface
{
    public function getConversionRatio(): ?BigNumber;
}
