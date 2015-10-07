<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper\GlobalData;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class MeasurementUnitI18n extends I18nBaseMapper
{
    protected $pull = [
        'measurementUnitId' => 'id',
        'name' => 'title',
        'languageISO' => null
    ];
}