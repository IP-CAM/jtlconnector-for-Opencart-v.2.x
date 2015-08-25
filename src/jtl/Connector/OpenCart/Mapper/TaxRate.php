<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class TaxRate extends BaseMapper
{
    protected $pull = [
        'id' => 'tax_rate_id',
        'rate' => 'rate'
    ];
}