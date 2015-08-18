<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\OpenCart\Utility\Utils;

class CategoryI18n extends DataMapper
{
    protected $pull = [
        'categoryId' => 'category_id',
        'description' => 'description',
        'languageISO' => 'language',
        'metaDescription' => 'meta_description',
        'metaKeywords' => 'meta_keyword',
        'name' => 'name'
    ];

    protected $push = [
        'category_id' => 'categoryId',
        'description' => 'description',
        'language' => 'languageISO',
        'meta_description' => 'metaDescription',
        'meta_keyword' => 'metaKeywords',
        'name' => 'name'
    ];

    protected function languageiso($data)
    {
        $language = Utils::getInstance()->getLanguages()[$data['language_id'] - 1];
        return $language->iso2;
    }


}