<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Product
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class ProductAttrI18n extends I18nBaseMapper
{
    protected $pull = [
        'productAttrId' => 'attribute_id',
        'languageISO' => null,
        'name' => 'name',
        'value' => 'value'
    ];
}