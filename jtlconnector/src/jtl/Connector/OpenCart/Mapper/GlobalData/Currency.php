<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper\GlobalData;

use jtl\Connector\Model\Currency as CurrencyModel;
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

    protected $push = [
        'title' => 'name',
        'code' => 'iso',
        'value' => 'factor',
        'symbol_left' => null,
        'symbol_right' => null,
        'decimal_place' => null,
        'status' => null
    ];

    protected function hasCurrencySignBeforeValue($data)
    {
        return isset($data['symbol_left']);
    }

    protected function nameHTML($data)
    {
        $symbol = (isset($data['symbol_left'])) ? $data['symbol_left'] : $data['symbol_right'];
        return htmlentities($symbol);
    }

    protected function symbol_left(CurrencyModel $data)
    {
        if ($data->getHasCurrencySignBeforeValue()) {
            return html_entity_decode($data->getNameHtml());
        }
        return '';
    }

    protected function symbol_right(CurrencyModel $data)
    {
        if (!$data->getHasCurrencySignBeforeValue()) {
            return html_entity_decode($data->getNameHtml());
        }
        return '';
    }

    protected function status()
    {
        return 1;
    }

    protected function decimal_place()
    {
        return 2;
    }
}