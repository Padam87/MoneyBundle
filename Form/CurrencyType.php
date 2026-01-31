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
    public function __construct(private array $config)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addModelTransformer(
                new CallbackTransformer(
                    fn(?Currency $modelData = null): ?string => $modelData !== null ? $modelData->getCurrencyCode() : null,
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
