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
        'languages' => 'GlobalData\Language',
        'currencies' => 'GlobalData\Currency',
        'taxRates' => 'GlobalData\TaxRate',
        'customerGroups' => 'GlobalData\CustomerGroup'
    ];

    public static function getModels()
    {
        return ['Language', 'Currency', 'TaxRate', 'CustomerGroup'];
    }
}