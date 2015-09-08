<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class ProductI18n extends I18nBaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'name' => 'name',
        'description' => 'description',
        'languageISO' => null,
        'metaDescription' => 'meta_description',
        'metaKeywords' => 'meta_keyword',
        'titleTag' => 'tag'
        // deliveryStatus, measurementUnitName, unitName, urlPath
    ];

    protected $push = [
        'name' => 'name',
        'description' => 'description',
        'tag' => 'titleTag',
        'meta_title' => null,
        'meta_description' => 'metaDescription',
        'meta_keyword' => 'metaKeywords'
    ];

    protected function meta_title()
    {
        return "";
    }
}