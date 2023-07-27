# Sdk for checkbox.in.ua

##### create checkbox object
```php
$checkbox = new Checkbox([
    'production'  => false,
    'license_key' => 'as1c9e4s8618d6d4fb5c22a0'
]);

```

##### signin
```php
$checkbox->singInCashier('login', 'password');
```

##### signout
```php
$checkbox->signOutCashier();
```

##### create shift
```php
$checkbox->createShift();
```

##### close shift
```php
$checkbox->closeShift();
```

##### create receipt

```php
$receipt = new Receipt;

// create good
$good = new ReceiptGood;
$good->setCode('pizza-1');
$good->setName('Піца Гавайська');
$good->setQuantity(1 * 1000);
$good->setPrice(114 * 100);
$receipt->adGood($good);

// create discount
$discount = new ReceiptDiscount;
$discount->setType(ReceiptDiscount::TYPE_DISCOUNT);
$discount->setMode(ReceiptDiscount::MODE_VALUE);
$discount->setValue(4 * 100);
$receipt->addDiscount($discount);

// create payment
$payment = new ReceiptPayment;
$payment->setType(ReceiptPayment::TYPE_CASHLESS);
$payment->setValue((114 - 4) * 100);
$receipt->addPayment($payment);

$receiptResponse = $checkbox->createReceipt($receipt);
```

##### get receipt as html
```php
$response = $checkbox->getReceiptHtml('16b03682-11bc-20fb-17fa-43749b4a3c5s');
```

##### get receipt as pdf
```php
$response = $checkbox->getReceiptPdf('16b03682-11bc-20fb-17fa-43749b4a3c5s');
```

##### get receipt as text
```php
$response = $checkbox->getReceiptText('16b03682-11bc-20fb-17fa-43749b4a3c5s');
```

##### get receipt as qrcode
```php
$response = $checkbox->getReceiptQrcode('16b03682-11bc-20fb-17fa-43749b4a3c5s');
```
