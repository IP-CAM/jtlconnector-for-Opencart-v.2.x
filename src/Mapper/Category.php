<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Mapper;

class Category extends BaseMapper
{
    protected $endpointModel = '\Category';

    protected $pull = [
        'id' => 'category_id',
        'parentCategoryId' => 'parent_id',
        'level' => 'column',
        'isActive' => 'status',
        'sort' => 'sort_order',
        'i18ns' => 'CategoryI18n'
    ];

    protected $push = [
        'category_id' => 'id',
        'parent_id' => 'parentCategoryId',
        'column' => 'level',
        'status' => 'isActive',
        'sort_order' => 'sort',
        'CategoryI18n' => 'i18ns'
    ];

}