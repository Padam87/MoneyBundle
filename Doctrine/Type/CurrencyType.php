<?php

namespace Padam87\MoneyBundle\Doctrine\Type;

use Brick\Money\Currency;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CurrencyType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = $this->getDefaultLength($platform); // enforce column length even if specified

        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultLength(AbstractPlatform $platform): int
    {
        return 3;
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Currency
    {
        if (null === $value) {
            return null;
        }

        return Currency::of($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof Currency) {
            throw new \LogicException(sprintf('Only instances of "%s" can be persisted as currency', Currency::class));
        }

        return (string) $value->getCurrencyCode();
    }
}
