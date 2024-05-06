# MoneyBundle

Symfony bundle for https://github.com/brick/money

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

#### A) Using the embedded money type

```php
    #[ORM\Embedded(class: EmbeddedMoney::class)]
    protected EmbeddedMoney $netPrice;

    public function getNetPrice(): ?Money
    {
        return ($this->netPrice)();
    }

    public function setNetPrice(?Money $netPrice): self
    {
        $this->netPrice = new EmbeddedMoney($netPrice);

        return $this;
    }
```

#### B) Using separate fields for amount and currency

_This is recommended when multiple amounts share the same currency_

```php
    use MoneyFromDecimalTrait;

    #[ORM\Column(type: 'currency')]
    private ?Currency $currency = null;

    #[ORM\Column(type: 'decimal_object')]
    private ?BigDecimal $netPrice = null;
    
    #[ORM\Column(type: 'decimal_object')]
    private ?BigDecimal $grossPrice = null;
    

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }
    
    
    public function getNetPrice(): Money
    {
        return $this->getMoney($this->netPrice);
    }

    protected function setNetPrice(Money $netPrice): self
    {
        $this->setMoney($this->netPrice, $netPrice);

        return $this;
    }
    
    
    public function getGrossPrice(): Money
    {
        return $this->getMoney($this->grossPrice);
    }

    protected function setGrossPrice(Money $grossPrice): self
    {
        $this->setMoney($this->grossPrice, $grossPrice);

        return $this;
    }
```

### Formatting

#### Twig

`{{ netPrice|money }}` -> €100

`{{ netPrice|money_amount }}` -> 100

`{{ netPrice|money_currency }}` -> €
