<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class MeasurementUnitI18n extends I18nBaseMapper
{
    protected $pull = [
        'measurement_unit_id' => 'id',
        'name' => 'title',
        'languageISO' => null
    ];
}