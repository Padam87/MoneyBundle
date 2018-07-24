<?php

namespace Padam87\MoneyBundle\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Money\Currency;

class CurrencyType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $fieldDeclaration['length'] = 3;

        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return new Currency($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value instanceof Currency) {
            throw new \LogicException();
        }

        return $value->getCode();
    }
}
