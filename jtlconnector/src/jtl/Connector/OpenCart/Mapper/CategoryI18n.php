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
        'name' => null,
        'description' => null,
        'languageISO' => null,
        'metaKeywords' => 'meta_keyword',
        'metaDescription' => 'meta_description'
    ];

    protected $push = [
        'category_id' => 'categoryId',
        'name' => 'name',
        'description' => 'description',
        'meta_keyword' => 'metaKeywords',
        'meta_description' => 'metaDescription',
        'meta_title' => null
    ];

    protected function name(array $data)
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