<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Specific
 */

namespace jtl\Connector\OpenCart\Mapper\Specific;

use jtl\Connector\Model\Specific as SpecificModel;
use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class SpecificValueI18n extends I18nBaseMapper
{
    protected $pull = [
        'specificValueId' => 'filter_id',
        'value' => 'name',
        'languageISO' => null
    ];

    protected $push = [
        'filter_id' => 'specificId',
        'name' => 'value'
    ];
}