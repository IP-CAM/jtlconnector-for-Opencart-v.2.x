<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\Model\CustomerOrderItem;

class OrderItemShippingMapper extends OrderItemBaseMapper
{
    protected function type()
    {
        return CustomerOrderItem::TYPE_SHIPPING;
    }
}