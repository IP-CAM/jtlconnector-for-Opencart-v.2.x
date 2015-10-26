<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

class CrossSelling extends BaseMapper
{
    protected $pull = [
        'id' => 'id',
        'productId' => 'product_id',
        'items' => 'CrossSellingItem'
    ];
}