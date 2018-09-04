<?php

namespace Padam87\MoneyBundle\Form;

use Money\Currency;
use Money\Money;
use Padam87\MoneyBundle\Service\MoneyHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyType extends AbstractType
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(
                new CallbackTransformer(
                    function (Currency $model = null) {
                        return $model ? $model->getCode() : null;
                    },
                    function ($form) {
                        return new Currency($form);
                    }
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'choices' => array_combine($this->config['currencies'], $this->config['currencies']),
                ]
            )
        ;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix()
    {
        return 'moneyphp_currency';
    }
}
