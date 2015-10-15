<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
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