<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

class ProductStockLevel extends BaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'stockLevel' => 'quantity'
    ];
}