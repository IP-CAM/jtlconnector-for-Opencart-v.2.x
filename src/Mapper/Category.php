<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\OpenCart\Utility\Constants;

class Category extends BaseMapper
{
    protected $endpointModel = Constants::CORE_MODEL_NAMESPACE . "Category";

    protected $pull = [
        'id' => 'category_id',
        'parentCategoryId' => 'parent_id',
        'level' => 'column',
        'isActive' => 'status',
        'sort' => 'sort_order',
        'i18ns' => 'CategoryI18n',
        // attributes, invisibilities
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