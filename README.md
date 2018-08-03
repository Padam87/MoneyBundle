# MoneyBundle

Symfony bundle for https://github.com/moneyphp/money

As an **opinionated** bundle, this money bundle uses the following principles as it's main guide:
- Storage is just as important as calculation.
- Financial data should be held in SQL, so Doctrine ORM only implementation.
- Money objects are Embeddables.
- Amount should be stored in a human readable way in the database.
- ISO money scale (eg smallest amount is 1 cent for EUR) is not viable for complex applications.

To achieve these, the following restrictions apply:
- precision and scale are mandatory (but have default values)
- amounts are mapped as DECIMAL (changable, but not recommended to change)
- ext-bcmath is mandatory.
(DECIMAL database values are converted to string by PDO.
This bundle uses bcmath to multiple these values by ˙pow(10, $scale)˙, and pass integer values to the `Money` object.
https://github.com/Padam87/MoneyBundle/blob/master/Doctrine/Type/MoneyAmountType.php#L45)

## Installation

`composer require padam87/money-bundle`

## Configuration (optional)

```yaml
padam87_money:
    precision:            18
    scale:                2
    currencies:

        # Default:
        - EUR
```

## Usage

### Doctrine

```php
    /**
     * @var Money
     *
     * @ORM\Embedded(class="Money\Money")
     */
    private $price;
```

### Formatting

The bundle adds 2 services.

`padam87_money.number_formatter` - A simple `\NumberFormatter` object, with the current request's locale, and currency style.

`Money\Formatter\IntlMoneyFormatter` - Intl money formatter
