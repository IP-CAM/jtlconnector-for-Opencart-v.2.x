<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Core\Utilities\Language;

class CategoryI18n extends BaseMapper
{
    protected $pull = [
        'categoryId' => 'category_id',
        'description' => 'description',
        'languageISO' => null,
        'metaDescription' => 'meta_description',
        'metaKeywords' => 'meta_keyword',
        'name' => 'name',
        //'titleTag',
        //'urlPath'
    ];

    /*protected $push = [
        'category_id' => 'categoryId',
        'description' => 'description',
        'language' => 'languageISO',
        'meta_description' => 'metaDescription',
        'meta_keyword' => 'metaKeywords',
        'name' => 'name'
    ];*/

    protected function languageISO($data)
    {
        return Language::convert($data['code']);
    }
}