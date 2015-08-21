<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Model\CustomerOrderItem as COI;

class CustomerOrderItem extends BaseMapper
{
    protected $pull = [
        'customerOrderId' => 'order_id',
        'id' => 'order_product_id',
        'productId' => 'product_id',
        'name' => 'name',
        'price' => null,
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

    protected function price($data)
    {
        if ($this->type($data) == COI::TYPE_PRODUCT) {
            return doubleval($data['price']);
        } else {
            return doubleval($data['value']);
        }
    }

}