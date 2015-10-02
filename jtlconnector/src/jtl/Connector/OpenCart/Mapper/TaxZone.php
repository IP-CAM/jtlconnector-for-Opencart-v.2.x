<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class TaxZone extends BaseMapper
{
    protected $pull = [
        'id' => 'geo_zone_id',
        'name' => 'name',
        'countries' => 'TaxZoneCountry'
    ];
}