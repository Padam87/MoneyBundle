<?php

namespace Padam87\MoneyBundle\Form;

use Brick\Math\BigDecimal;
use Padam87\MoneyBundle\Money\Context\BundleContext;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecimalType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addModelTransformer(
                new CallbackTransformer(
                    function (?BigDecimal $modelData = null) use ($options): ?string {
                        if ($modelData === null) {
                            return null;
                        }

                        if ($options['integer_only']) {
                            return $modelData->getIntegralPart();
                        }

                        return (string) $modelData;
                    },
                    function (?string $formData): ?BigDecimal {
                        if ($formData === null) {
                            return null;
                        }

                        return BigDecimal::of(str_replace([' ', ','], ['', '.'], $formData));
                    }
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    'integer_only' => false,
                    'html5' => true,
                    'scale' => BundleContext::getScale(),
                ]
            )
        ;
    }

    public function getParent(): ?string
    {
        return NumberType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'decimal';
    }
}
