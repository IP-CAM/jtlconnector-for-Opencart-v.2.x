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
        'sort_order' => 'sort',
        'type' => null,
        'required' => null
    ];

    protected function type(ProductVariationModel $data)
    {
        switch ($data->getType()) {
            case ProductVariationModel::TYPE_IMAGE_SWATCHES:
                return 'select';
            case ProductVariationModel::TYPE_TEXTBOX:
                return 'select';
            case ProductVariationModel::TYPE_FREE_TEXT:
                return 'text';
            case ProductVariationModel::TYPE_FREE_TEXT_OBLIGATORY:
                return 'text';
        }
        return $data->getType();
    }

    protected function required(ProductVariationModel $data)
    {
        return $data->getType() !== ProductVariationModel::TYPE_FREE_TEXT;
    }
}