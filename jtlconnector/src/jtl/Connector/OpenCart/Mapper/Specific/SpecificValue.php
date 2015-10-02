<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Specific
 */

namespace jtl\Connector\OpenCart\Mapper\Specific;

use jtl\Connector\Model\Specific as SpecificModel;
use jtl\Connector\OpenCart\Mapper\BaseMapper;

class SpecificValue extends BaseMapper
{
    protected $pull = [
        'id' => 'filter_id',
        'specificId' => 'filter_group_id',
        'sort' => 'sort_order',
        'i18ns' => 'Specific\SpecificValueI18n'
    ];

    protected $push = [
        'filter_id' => 'id',
        'filter_group_id' => 'specificId',
        'sort_order' => 'sort'
    ];
}