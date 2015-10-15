<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\GlobalData;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class MeasurementUnit extends BaseMapper
{
    protected $pull = [
        'id' => 'id',
        'displayCode' => 'unit',
        'i18ns' => 'GlobalData\MeasurementUnitI18n'
    ];
}