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

class OrderItemProductMapper extends BaseMapper
{
    protected $pull = [
        'customerOrderId' => 'order_id',
        'productId' => 'product_id',
        'name' => 'name',
        'price' => 'price',
        'quantity' => 'quantity',
        'vat' => 'tax',
        'sku' => 'sku',
        'type' => null,
        //TODO
        //'variations' => '\jtl\Connector\Model\CustomerOrderItemVariation'
    ];

    public function __construct()
    {
        $this->database = DB::getInstance();
        $this->model = Constants::CORE_MODEL_NAMESPACE . 'CustomerOrderItem';
        $this->type = new CustomerOrderItemType();
    }


    protected function type($data)
    {
        return CustomerOrderItem::TYPE_PRODUCT;
    }
}