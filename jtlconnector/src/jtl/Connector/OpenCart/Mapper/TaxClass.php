<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

class TaxClass extends BaseMapper
{
    protected $pull = [
        'id' => 'tax_class_id',
        'name' => 'title'
    ];
}