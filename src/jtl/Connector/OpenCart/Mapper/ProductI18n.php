<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

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
}