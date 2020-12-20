<?php

namespace Kolirt\Checkbox;

class ReceiptDiscount
{

    const TYPE_DISCOUNT     = 'DISCOUNT';
    const TYPE_EXTRA_CHARGE = 'EXTRA_CHARGE';

    const MODE_PERCENT = 'PERCENT';
    const MODE_VALUE   = 'VALUE';

    private $type;
    private $mode;
    private $value;
    private $tax_code;
    private $name;

    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    public function setMode(string $mode)
    {
        $this->mode = $mode;
        return $this;
    }

    public function setValue(float $value)
    {
        $this->value = $value;
        return $this;
    }

    public function setTaxCode(int $tax_code)
    {
        $this->tax_code = $tax_code;
        return $this;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function render()
    {
        return [
            'type'     => $this->type,
            'mode'     => $this->mode,
            'value'    => $this->value,
            'tax_code' => $this->tax_code,
            'name'     => $this->name,
        ];
    }

}