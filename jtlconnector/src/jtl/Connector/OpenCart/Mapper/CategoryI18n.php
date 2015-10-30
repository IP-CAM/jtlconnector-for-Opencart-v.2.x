<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

class CategoryI18n extends I18nBaseMapper
{
    protected $pull = [
        'categoryId' => 'category_id',
        'titleTag' => 'meta_title',
        'metaKeywords' => 'meta_keyword',
        'metaDescription' => 'meta_description',
        'name' => null,
        'description' => null,
        'languageISO' => null,
    ];

    protected $push = [
        'category_id' => 'categoryId',
        'name' => 'name',
        'description' => 'description',
        'meta_title' => 'titleTag',
        'meta_keyword' => 'metaKeywords',
        'meta_description' => 'metaDescription'
    ];

    protected function name(array $data)
    {
        return isset($data['name']) ? html_entity_decode($data['name']) : '';
    }

    protected function description($data)
    {
        return isset($data['description']) ? html_entity_decode($data['description']) : '';
    }
}