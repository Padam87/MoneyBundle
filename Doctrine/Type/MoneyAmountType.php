<?php

namespace Padam87\MoneyBundle\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DecimalType;

class MoneyAmountType extends DecimalType
{
    /**
     * This value is set by the configuration
     *
     * @var int
     */
    static $precision;

    /**
     * This value is set by the configuration
     *
     * @var int
     */
    static $scale;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'money_amount';
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
        return bcmul($value, pow(10, self::$scale), 0);
    }
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return bcdiv($value, pow(10, self::$scale), self::$scale);
    }
}
