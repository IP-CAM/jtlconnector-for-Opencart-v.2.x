<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

class TaxZoneCountry extends BaseMapper
{
    protected $pull = [
        'taxZoneId' => 'geo_zone_id',
        'country_iso' => 'iso_code_2'
    ];
}