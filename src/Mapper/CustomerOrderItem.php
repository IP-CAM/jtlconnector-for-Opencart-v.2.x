<?php

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Model\CustomerOrderItem as COI;

class CustomerOrderItem extends BaseMapper
{
    protected $pull = [
        'customerOrderId' => 'order_id',
        'id' => 'order_product_id',
        'productId' => 'product_id',
        'name' => 'name',
        'price' => 'price',
        'quantity' => 'quantity',
        'vat' => 'tax',
        'sku' => 'sku',
        'type' => null,
        //'configItemId' => 'Identity',
        // TODO: Together with products
        //'variations' => '\jtl\Connector\Model\CustomerOrderItemVariation'
    ];

    protected function type($data)
    {
        if (!isset($data['code'])) {
            return null;
        }
        switch ($data['code']) {
            case 'shipping':
                return COI::TYPE_SHIPPING;
            case 'coupon':
                return COI::TYPE_DISCOUNT;
            case 'voucher':
                return COI::TYPE_DISCOUNT;
            default:
                return COI::TYPE_PRODUCT;
        }
    }

}