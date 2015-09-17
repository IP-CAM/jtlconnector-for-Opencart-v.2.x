<?php

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\Model\ProductVariationValue as PVVModel;
use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductVariationValue extends BaseMapper
{
    protected $pull = [
        'id' => 'product_option_value_id',
        'productVariationId' => 'product_option_id',
        'extraWeight' => null,
        'stockLevel' => 'quantity',
        'i18ns' => 'Product\ProductVariationValueI18n',
        'extraCharges' => 'Product\ProductVariationValueExtraCharge'
    ];

    protected $push = [
        'product_option_value_id' => 'id',
        'quantity' => 'stockLevel',
        'weight' => null,
        'weight_prefix' => null,
        'subtract' => null,
        'price_prefix' => null,
        'price' => null,
        'points_prefix' => null,
        'points' => null
    ];

    protected function extraWeight($data)
    {
        if ($data['weight_prefix'] == '+') {
            return doubleval($data['weight']);
        }
        return 0.0;
    }

    protected function subtract()
    {
        return 0;
    }

    protected function weight(PVVModel $data)
    {
        return abs($data->getExtraWeight());
    }

    protected function weight_prefix(PVVModel $data)
    {
        return (strrpos('-', $data->getExtraWeight()) === false) ? '+' : '-';
    }

    protected function price_prefix(PVVModel $data)
    {
        return ($this->price($data) >= 0) ? '+' : '-';
    }

    protected function price(PVVModel $data)
    {
        $price = 0;
        foreach ($data->getExtraCharges() as $extra) {
            $price += $extra->getExtraChargeNet();
        }
        return $price;
    }

    protected function points_prefix()
    {
        return '+';
    }

    protected function points()
    {
        return 0;
    }
}