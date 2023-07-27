<?php

namespace Kolirt\Checkbox;

class ReceiptPayment
{

    const TYPE_CASH     = 'CASH';
    const TYPE_CASHLESS = 'CASHLESS';
    const TYPE_CARD     = 'CARD';

    private $type;
    private $code;
    private $value;
    private $label;
    private $card_mask;
    private $bank_name;
    private $auth_code;
    private $rrn;
    private $payment_system;
    private $owner_name;

    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    public function setCode(int $code)
    {
        $this->code = $code;
        return $this;
    }

    public function setValue(float $value)
    {
        $this->value = $value ;
        return $this;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
        return $this;
    }

    public function setCardMask(string $card_mask)
    {
        $this->card_mask = $card_mask;
        return $this;
    }

    public function setBankName(string $bank_name)
    {
        $this->bank_name = $bank_name;
        return $this;
    }

    public function setAuthCode(string $auth_code)
    {
        $this->auth_code = $auth_code;
        return $this;
    }

    public function setRrn(string $rrn)
    {
        $this->rrn = $rrn;
        return $this;
    }

    public function setPaymentSystem(string $payment_system)
    {
        $this->payment_system = $payment_system;
        return $this;
    }

    public function setOwnerName(string $owner_name)
    {
        $this->owner_name = $owner_name;
        return $this;
    }

    public function render()
    {
        if ($this->type == self::TYPE_CASH) {
            return [
                'type'  => $this->type,
                'value' => $this->value,
                'label' => $this->label ?? 'Готівка'
            ];
        }

        return [
            'type'           => $this->type,
            'code'           => $this->code,
            'value'          => $this->value,
            'label'          => $this->label ?? 'Картка',
            'card_mask'      => $this->card_mask,
            'bank_name'      => $this->bank_name,
            'auth_code'      => $this->auth_code,
            'rrn'            => $this->rrn,
            'payment_system' => $this->payment_system,
            'owner_name'     => $this->owner_name
        ];
    }

}