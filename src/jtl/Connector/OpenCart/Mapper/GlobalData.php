<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class GlobalData extends BaseMapper
{
    protected $pull = [
        'languages' => 'Language',
        'currencies' => 'Currency',
        'taxRates' => 'TaxRate',
        'customerGroups' => 'CustomerGroup'
    ];
}