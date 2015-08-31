<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper\GlobalData;

use jtl\Connector\OpenCart\Mapper\BaseMapper;


class Currency extends BaseMapper
{
    protected $pull = [
        'id' => 'currency_id',
        'factor' => 'value',
        'name' => 'title',
        'iso' => 'code',
        'hasCurrencySignBeforeValue' => null,
        'nameHTML' => null
    ];

    protected function hasCurrencySignBeforeValue($data)
    {
        return isset($data['symbol_left']);
    }

    protected function nameHTML($data)
    {
        $symbol = (isset($data['symbol_left'])) ? $data['symbol_left'] : $data['symbol_right'];
        return html_entity_decode($symbol);
    }
}