<?php

namespace Padam87\MoneyBundle\Form;

use Brick\Money\Currency;
use Brick\Money\Money;
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', $options['amount_type'], $options['amount_options'])
            ->addModelTransformer(
                new CallbackTransformer(
                    function (?Money $modelData = null) {
                        if ($modelData === null) {
                            return null;
                        }

                        return [
                            'amount' => $modelData->getAmount(),
                            'currency' => $modelData->getCurrency(),
                        ];
                    },
                    function (?array $formData = null) use ($options) {
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

                        return Money::of($amount, $currency);
                    }
                )
            )
        ;

        if ($options['currency_enabled']) {
            $builder->add('currency', $options['currency_type'], $options['currency_options']);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['currency_enabled'] = $options['currency_enabled'];
        $view->vars['default_currency_code'] = $options['default_currency_code'];
    }

    public function configureOptions(OptionsResolver $resolver)
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
                ]
            )
        ;
    }

    public function getBlockPrefix()
    {
        return 'money_object';
    }
}
