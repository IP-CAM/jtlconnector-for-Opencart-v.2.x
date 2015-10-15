<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Product
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class ProductI18n extends I18nBaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'name' => null,
        'description' => null,
        'languageISO' => null,
        'titleTag' => 'tag',
        'metaKeywords' => 'meta_keyword',
        'metaDescription' => 'meta_description',
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

    protected function name($data)
    {
        return html_entity_decode($data['name']);
    }

    protected function description($data)
    {
        return html_entity_decode($data['description']);
    }

    protected function meta_title()
    {
        return "";
    }
}