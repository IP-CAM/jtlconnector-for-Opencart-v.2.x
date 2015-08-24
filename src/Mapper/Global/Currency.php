<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class Currency extends BaseMapper
{
    protected $pull = [
        'id' => 'currency_id',
        'factor' => 1,
        'name' => 'title',
        'iso' => 'code',
        'hasCurrencySignBeforeValue' => null
    ];

    protected function hasCurrencySignBeforeValue($data)
    {
        return isset($data['symbol_left']);
    }
}