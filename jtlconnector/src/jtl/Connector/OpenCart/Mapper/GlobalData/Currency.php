<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\GlobalData;

use jtl\Connector\Model\Currency as CurrencyModel;
use jtl\Connector\OpenCart\Mapper\BaseMapper;

class Currency extends BaseMapper
{
    protected $pull = [
        'id' => 'currency_id',
        'name' => 'title',
        'factor' => 'value',
        'iso' => 'code',
        'isDefault' => 'is_default',
        'nameHTML' => null,
        'hasCurrencySignBeforeValue' => null
    ];

    protected $push = [
        'currency_id' => 'id',
        'title' => 'name',
        'value' => 'factor',
        'code' => 'iso',
        'symbol_left' => null,
        'symbol_right' => null,
        'decimal_place' => null,
        'status' => null
    ];

    protected function hasCurrencySignBeforeValue($data)
    {
        return !empty($data['symbol_left']);
    }

    protected function nameHTML($data)
    {
        $symbol = empty($data['symbol_left']) ? $data['symbol_right'] : $data['symbol_left'];
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