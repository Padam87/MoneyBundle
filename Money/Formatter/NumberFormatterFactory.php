<?php

namespace Padam87\MoneyBundle\Money\Formatter;

use Symfony\Component\HttpFoundation\RequestStack;

class NumberFormatterFactory
{
    public static function createNumberFormatter(RequestStack $requestStack)
    {
        return new \NumberFormatter($requestStack->getCurrentRequest()->getLocale(), \NumberFormatter::CURRENCY);
    }
}
