<?php

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\Model\ProductVariation as ProductVariationModel;
use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductVariation extends BaseMapper
{
    protected $pull = [
        'id' => 'product_option_id',
        'productId' => 'product_id',
        'sort' => 'sort_order',
        'i18ns' => 'Product\ProductVariationI18n',
        'values' => 'Product\ProductVariationValue'
    ];

    protected $push = [
        'product_option_id' => 'id',
        //'product_id' => 'productId',
        //'sort_order' => 'sort',
        'type' => null,
        'required' => null,
        'Product\ProductVariationI18n' => 'i18ns',
        'Product\ProductVariationValue' => 'values'
    ];

    protected function type(ProductVariationModel $data)
    {
        return count($data->getValues()) > 1 ? 'select' : 'text';
    }

    protected function required()
    {
        return true;
    }
}