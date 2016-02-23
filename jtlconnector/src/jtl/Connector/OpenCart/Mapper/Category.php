<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Model\Category as CategoryModel;

class Category extends BaseMapper
{
    protected $pull = [
        'id' => 'category_id',
        'parentCategoryId' => 'parent_id',
        'isActive' => 'status',
        'sort' => 'sort_order',
        'level' => 'level',
        'i18ns' => 'CategoryI18n'
    ];

    protected $push = [
        'category_id' => 'id',
        'parent_id' => 'parentCategoryId',
        'status' => 'isActive',
        'sort_order' => 'sort',
        'level' => 'level',
        'CategoryI18n' => 'i18ns',
        'top' => null,
        'column' => null,
        'keyword' => null,
        'category_store' => null
    ];

    protected function top(CategoryModel $data)
    {
        return $data->getParentCategoryId()->getHost() === 0;
    }

    protected function column()
    {
        return 0;
    }

    protected function keyword()
    {
        return '';
    }

    protected function category_store()
    {
        return [0];
    }
}