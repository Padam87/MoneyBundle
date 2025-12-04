<?php

namespace Padam87\MoneyBundle\Doctrine\Type;

use Brick\Math\BigDecimal;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class DecimalObjectType extends Type
{
    public function __construct(private array $config = [])
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'decimal_object';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if (!isset($column['precision'])) {
            $column['precision'] = $this->config['precision'];
        }

        if (!isset($column['scale'])) {
            $column['scale'] = $this->config['scale'];
        }

        return $platform->getDecimalTypeDeclarationSQL($column);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?BigDecimal
    {
        if (null === $value) {
            return null;
        }

        return BigDecimal::of($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
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
