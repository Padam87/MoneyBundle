<?php

namespace Padam87\MoneyBundle\Doctrine\Mapping\Driver;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Padam87\MoneyBundle\Money\EmbeddedMoney;
use Padam87\MoneyBundle\Money\NullableMoney;

class MoneyEmbeddedDriver implements MappingDriver
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass($className, ClassMetadata $metadata)
    {
        /* @var \Doctrine\ORM\Mapping\ClassMetadataInfo $metadata */

        $metadata->isEmbeddedClass = true;

        $metadata->mapField(
            [
                'fieldName' => 'amount',
                'type' => 'decimal_object',
                'precision' => $this->config['precision'],
                'scale' => $this->config['scale'],
                'nullable' => $className === NullableMoney::class,
            ]
        );

        $metadata->mapField(
            [
                'fieldName' => 'currency',
                'type' => 'currency',
                'nullable' => $className === NullableMoney::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames()
    {
        return [
            EmbeddedMoney::class,
            NullableMoney::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function isTransient($className)
    {
        return false;
    }
}
