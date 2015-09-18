<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Product
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class ProductVariationI18n extends I18nBaseMapper
{
    protected $pull = [
        'productVariationId' => 'product_option_id',
        'languageISO' => null,
        'name' => 'name'
    ];

    protected $push = [
        'name' => 'name'
    ];
}