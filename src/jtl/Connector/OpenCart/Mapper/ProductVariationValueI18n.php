<?php

namespace jtl\Connector\OpenCart\Mapper;

class ProductVariationValueI18n extends I18nBaseMapper
{
    protected $pull = [
        'productVariationValueId' => 'product_option_value_id',
        'languageISO' => null,
        'name' => 'name'
    ];
}