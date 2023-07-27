<?php

namespace Kolirt\Checkbox;

class ReceiptGood
{

    private $good = [
        'code'    => null,
        'name'    => null,
        'barcode' => null,
        'header'  => null,
        'footer'  => null,
        'price'   => null,
        'tax'     => null,
        'uktzed'  => null,
    ];
    private $good_id;
    private $quantity;
    private $is_return = false;
    private $discounts = [];

    public function setCode(string $code)
    {
        $this->good['code'] = $code;
        return $this;
    }

    public function setName(string $name)
    {
        $this->good['name'] = $name;
        return $this;
    }

    public function setBarcode(string $barcode)
    {
        $this->good['barcode'] = $barcode;
        return $barcode;
    }

    public function setHeader(string $header)
    {
        $this->good['header'] = $header;
        return $this;
    }

    public function setFooter(string $footer)
    {
        $this->good['footer'] = $footer;
        return $this;
    }

    public function setPrice(float $price)
    {
        $this->good['price'] = $price;
        return $this;
    }

    public function setTax(array $tax)
    {
        $this->good['tax'] = $tax;
        return $this;
    }

    public function setUktzed(string $uktzed)
    {
        $this->good['uktzed'] = $uktzed;
        return $this;
    }

    public function setGoodId(string $uuid)
    {
        $this->good_id = $uuid;
        return $this;
    }

    public function setQuantity(float $quantity)
    {
        $this->quantity = $quantity ;
        return $this;
    }

    public function setIsReturn(bool $is_return)
    {
        $this->is_return = $is_return;
        return $this;
    }

    public function adDiscount(ReceiptDiscount $discount)
    {
        $this->discounts[] = $discount;
        return $this;
    }

    public function render()
    {
        return [
            'good'      => $this->good,
            'good_id'   => $this->good_id,
            'quantity'  => $this->quantity,
            'is_return' => $this->is_return,
            'discounts' => array_map(function (ReceiptDiscount $discount) {
                return $discount->render();
            }, $this->discounts)
        ];
    }

}