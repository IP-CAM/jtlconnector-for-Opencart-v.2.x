<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class CategoryI18n extends I18nBaseMapper
{
    protected $pull = [
        'categoryId' => 'category_id',
        'name' => 'name',
        'description' => 'description',
        'languageISO' => null,
        'metaDescription' => 'meta_description',
        'metaKeywords' => 'meta_keyword'
    ];

    protected $push = [
        'category_id' => 'categoryId',
        'name' => 'name',
        'description' => 'description',
        'meta_description' => 'metaDescription',
        'meta_keyword' => 'metaKeywords',
        'meta_title' => null
    ];

    protected function meta_title($data)
    {
        return "";
    }
}