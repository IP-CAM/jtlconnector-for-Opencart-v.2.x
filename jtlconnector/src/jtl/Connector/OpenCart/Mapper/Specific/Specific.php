<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Specific
 */

namespace jtl\Connector\OpenCart\Mapper\Specific;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class Specific extends BaseMapper
{
    protected $pull = [
        'id' => 'filter_group_id',
        'sort' => 'sort_order',
        'i18ns' => 'Specific\SpecificI18n',
        'values' => 'Specific\SpecificValue'
    ];

    protected $push = [
        'filter_group_id' => 'id',
        'sort_order' => 'sort',
        'Specific\SpecificI18n' => 'i18ns'
    ];
}