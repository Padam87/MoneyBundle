<?php

namespace Padam87\MoneyBundle\Money\Formatter;

use Symfony\Component\HttpFoundation\RequestStack;

class NumberFormatterFactory
{
    public static function createNumberFormatter(RequestStack $requestStack, string $defaultLocale)
    {
        if (null === $request = $requestStack->getCurrentRequest()) {
            $locale = $defaultLocale;
        } else {
            $locale = $requestStack->getCurrentRequest()->getLocale();
        }

        return new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
    }
}
