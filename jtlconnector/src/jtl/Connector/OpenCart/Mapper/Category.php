<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class Category extends BaseMapper
{
    protected $pull = [
        'id' => 'category_id',
        'parentCategoryId' => 'parent_id',
        'isActive' => 'status',
        'sort' => 'sort_order',
        'i18ns' => 'CategoryI18n'
    ];

    protected $push = [
        'category_id' => 'id',
        'parent_id' => 'parentCategoryId',
        'status' => 'isActive',
        'sort_order' => 'sort',
        'CategoryI18n' => 'i18ns',
        'category_store' => null,
        'top' => null,
    ];

    protected function category_store($data)
    {
        return [intval(0)];
    }

    protected function top($data)
    {
        $parentId = $data->getParentCategoryId()->getHost();
        return $parentId === 0;
    }
}