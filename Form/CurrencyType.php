<?php

namespace Padam87\MoneyBundle\Form;

use Brick\Money\Currency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyType extends AbstractType
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addModelTransformer(
                new CallbackTransformer(
                    function (?Currency $modelData = null): ?string {
                        return $modelData !== null ? $modelData->getCurrencyCode() : null;
                    },
                    function ($formData): ?Currency {
                        if ($formData === null) {
                            return null;
                        }

                        return Currency::of($formData);
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
                    'choices' => array_combine($this->config['currencies'], $this->config['currencies']),
                ]
            )
        ;
    }

    public function getParent(): ?string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'moneyphp_currency';
    }
}
