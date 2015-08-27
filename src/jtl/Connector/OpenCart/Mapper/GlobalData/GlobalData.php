<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper\GlobalData;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class GlobalData extends BaseMapper
{
    protected $pull = [
        'languages' => 'Language',
        'currencies' => 'Currency',
        'taxRates' => 'TaxRate',
        'customerGroups' => 'CustomerGroup'
    ];

    public static function getModels()
    {
        return array_values((new GlobalData())->pull);
    }
}