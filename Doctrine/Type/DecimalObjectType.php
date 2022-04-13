<?php

namespace Padam87\MoneyBundle\Doctrine\Type;

use Brick\Math\BigDecimal;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DecimalType;

class DecimalObjectType extends DecimalType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'decimal_object';
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

        return BigDecimal::of($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof BigDecimal) {
            throw new \LogicException(sprintf('Only instances of "%s" can be persisted as decimal', BigDecimal::class));
        }

        return (string) $value;
    }
}
