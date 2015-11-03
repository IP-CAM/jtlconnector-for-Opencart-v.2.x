<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\Model\CustomerOrderItem;

class OrderItemProductMapper extends OrderItemBaseMapper
{
    protected $pull = [
        'id' => 'order_item_id',
        'productId' => 'product_id',
        'customerOrderId' => 'order_id',
        'sku' => 'sku',
        'name' => 'name',
        'price' => 'price',
        'quantity' => 'quantity',
        'variations' => 'Order\CustomerOrderItemVariation',
        'type' => null
    ];

    protected function type()
    {
        return CustomerOrderItem::TYPE_PRODUCT;
    }
}