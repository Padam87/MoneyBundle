<?php

namespace Padam87\MoneyBundle\Doctrine\Mapping\Driver;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Money\CurrencyPair;

class CurrencyPairEmbeddedDriver implements MappingDriver
{
    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass($className, ClassMetadata $metadata)
    {
        /* @var \Doctrine\ORM\Mapping\ClassMetadataInfo $metadata */

        $metadata->isEmbeddedClass = true;

        $metadata->mapField(
            [
                'fieldName' => 'baseCurrency',
                'type' => 'currency',
            ]
        );

        $metadata->mapField(
            [
                'fieldName' => 'counterCurrency',
                'type' => 'currency',
            ]
        );

        $metadata->mapField(
            [
                'fieldName' => 'conversionRatio',
                'type' => 'float',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames()
    {
        return [
            CurrencyPair::class
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
