<?php

namespace Padam87\MoneyBundle\Form;

use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\Money;
use Padam87\MoneyBundle\Money\Context\BundleContext;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', $options['amount_type'], $options['amount_options'])
            ->addModelTransformer(
                new CallbackTransformer(
                    function (?Money $modelData = null): ?array {
                        if ($modelData === null) {
                            return null;
                        }

                        return [
                            'amount' => $modelData->getAmount(),
                            'currency' => $modelData->getCurrency(),
                        ];
                    },
                    function (?array $formData = null) use ($options): ?Money {
                        if ($formData === null) {
                            return null;
                        }

                        if (null === $amount = $formData['amount']) {
                            return null;
                        }

                        if (array_key_exists('currency', $formData)) {
                            $currency = $formData['currency'];
                        } else {
                            $currency = Currency::of($options['default_currency_code']);
                        }

                        return Money::of($amount, $currency, $options['context'], $options['rounding_mode']);
                    }
                )
            )
        ;

        if ($options['currency_enabled']) {
            $builder->add('currency', $options['currency_type'], $options['currency_options']);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['currency_enabled'] = $options['currency_enabled'];
        $view->vars['default_currency_code'] = $options['default_currency_code'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    'amount_type' => DecimalType::class,
                    'amount_options' => [
                        'label' => false,
                    ],
                    'default_currency_code' => 'HUF',
                    'currency_enabled' => false,
                    'currency_type' => CurrencyType::class,
                    'currency_options' => [
                        'label' => false,
                    ],
                    'addon_text' => null,
                    'context' => new BundleContext(),
                    'rounding_mode' => RoundingMode::UNNECESSARY,
                ]
            )
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'money_object';
    }
}
