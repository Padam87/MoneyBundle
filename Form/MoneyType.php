<?php

namespace Padam87\MoneyBundle\Form;

use Money\Currency;
use Money\Money;
use Padam87\MoneyBundle\Service\MoneyHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneyType extends AbstractType
{
    private $moneyHelper;

    public function __construct(MoneyHelper $moneyHelper)
    {
        $this->moneyHelper = $moneyHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', $options['amount_type'], $options['amount_options'])
            ->addModelTransformer(
                new CallbackTransformer(
                    function (Money $model = null) use ($options) {
                        if ($model === null) {
                            $model = new Money(0, new Currency($options['default_currency_code']));
                        }

                        return [
                            'amount' => $this->moneyHelper->getAmount($model),
                            'currency' => $model->getCurrency(),
                        ];
                    },
                    function ($form) {
                        return $this->moneyHelper->createMoney($form['amount'], $form['currency']);
                    }
                )
            )
        ;

        if ($options['currency_enabled']) {
            $builder->add('currency', $options['currency_type'], $options['currency_options']);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'amount_type' => TextType::class,
                    'amount_options' => [
                        'label' => false,
                    ],
                    'default_currency_code' => 'EUR',
                    'currency_enabled' => false,
                    'currency_type' => CurrencyType::class,
                    'currency_options' => [
                        'label' => false,
                    ],
                ]
            )
        ;
    }

    public function getBlockPrefix()
    {
        return 'moneyphp_money';
    }
}
