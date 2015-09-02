<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Order
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\Model\CustomerOrderItem;
use jtl\Connector\OpenCart\Mapper\BaseMapper;
use jtl\Connector\OpenCart\Utility\Constants;
use jtl\Connector\OpenCart\Utility\Db;
use jtl\Connector\Type\CustomerOrderItem as CustomerOrderItemType;

class OrderItemDiscountMapper extends BaseMapper
{
    protected $pull = [
        'id' => 'order_item_id',
        'customerOrderId' => 'order_id',
        'name' => 'title',
        'price' => 'value',
        'quantity' => null,
        'type' => null
    ];

    public function __construct()
    {
        $this->database = DB::getInstance();
        $this->model = Constants::CORE_MODEL_NAMESPACE . 'CustomerOrderItem';
        $this->type = new CustomerOrderItemType();
    }

    protected function type($data)
    {
        return CustomerOrderItem::TYPE_DISCOUNT;
    }

    protected function quantity($data)
    {
        return doubleval(1);
    }
}