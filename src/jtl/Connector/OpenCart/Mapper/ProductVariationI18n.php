<?php

namespace jtl\Connector\OpenCart\Mapper;

class ProductVariationI18n extends I18nBaseMapper
{
    protected $pull = [
        'productVariationId' => 'product_option_id',
        'languageISO' => null,
        'name' => 'name'
    ];
}