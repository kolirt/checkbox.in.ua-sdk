<?php

namespace Kolirt\Checkbox;

class Receipt
{

    private $id;
    private $cashier_name;
    private $departament;
    private $goods = [];
    private $delivery;
    private $discounts = [];
    private $payments = [];
    private $rounding = false;
    private $header;
    private $footer;
    private $barcode;
    private $order_id;

    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }

    public function setCashierName(string $cashier_name)
    {
        $this->cashier_name = $cashier_name;
        return $this;
    }

    public function setDepartament(string $departament)
    {
        $this->departament = $departament;
        return $this;
    }

    public function adGood(ReceiptGood $good)
    {
        $this->goods[] = $good;
        return $this;
    }

    public function setDeliveryEmail(string $email)
    {
        $this->delivery = [
            'email' => $email
        ];
        return $this;
    }

    public function addDiscount(ReceiptDiscount $discount)
    {
        $this->discounts[] = $discount;
        return $this;
    }

    public function addPayment(ReceiptPayment $payment)
    {
        $this->payments[] = $payment;
        return $this;
    }

    public function setRounding(bool $rounding)
    {
        $this->rounding = $rounding;
        return $this;
    }

    public function setHeader(string $header)
    {
        $this->header = $header;
        return $this;
    }

    public function setFooter(string $footer)
    {
        $this->footer = $footer;
        return $this;
    }

    public function setBarcode(string $barcode)
    {
        $this->barcode = $barcode;
        return $this;
    }

    public function setOrderId(string $order_id)
    {
        $this->order_id = $order_id;
        return $this;
    }

    public function render()
    {
        return [
            'id'           => $this->id,
            'cashier_name' => $this->cashier_name,
            'departament'  => $this->departament,
            'goods'        => array_map(function (ReceiptGood $good) {
                return $good->render();
            }, $this->goods),
            'delivery'     => $this->delivery,
            'discounts'    => array_map(function (ReceiptDiscount $discout) {
                return $discout->render();
            }, $this->discounts),
            'payments'     => array_map(function (ReceiptPayment $payment) {
                return $payment->render();
            }, $this->payments),
            'rounding'     => $this->rounding,
            'header'       => $this->header,
            'footer'       => $this->footer,
            'barcode'      => $this->barcode,
            'order_id'     => $this->order_id,
        ];

    }

}