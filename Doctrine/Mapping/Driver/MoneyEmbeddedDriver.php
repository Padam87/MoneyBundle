<?php

namespace Padam87\MoneyBundle\Doctrine\Mapping\Driver;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Money\Money;

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
                'type' => 'money_amount',
                'precision' => $this->config['precision'],
                'scale' => $this->config['scale'],
            ]
        );

        $metadata->mapField(
            [
                'fieldName' => 'currency',
                'type' => 'currency',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames()
    {
        return [
            Money::class
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
