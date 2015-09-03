<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class TaxClass extends BaseMapper
{
    protected $pull = [
        'id' => 'tax_class_id',
        'name' => 'title'
    ];
}