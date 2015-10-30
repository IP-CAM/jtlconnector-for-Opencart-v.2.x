<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class ProductI18n extends I18nBaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'titleTag' => 'tag',
        'metaKeywords' => 'meta_keyword',
        'metaDescription' => 'meta_description',
        'name' => null,
        'description' => null,
        'languageISO' => null,
    ];

    protected $push = [
        'name' => 'name',
        'description' => 'description',
        'tag' => 'titleTag',
        'meta_keyword' => 'metaKeywords',
        'meta_description' => 'metaDescription',
        'meta_title' => null
    ];

    protected function name($data)
    {
        return isset($data['name']) ? html_entity_decode($data['name']) : '';
    }

    protected function description($data)
    {
        return isset($data['description']) ? html_entity_decode($data['description']) : '';
    }

    protected function meta_title()
    {
        return '';
    }
}