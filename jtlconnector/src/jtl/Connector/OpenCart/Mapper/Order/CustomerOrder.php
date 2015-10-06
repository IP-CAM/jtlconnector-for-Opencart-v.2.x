<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Order
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class CustomerOrder extends I18nBaseMapper
{
    const SHIPPING_ID_PREFIX = "s_";
    const BILLING_ID_PREFIX = "b_";

    protected $pull = [
        'id' => 'order_id',
        'orderNumber' => 'order_id',
        'customerId' => 'customer_id',
        'creationDate' => 'date_added',
        'currencyIso' => 'currency_code',
        'languageISO' => null,
        'note' => 'comment',
        'totalSum' => 'total',
        'shippingMethodName' => 'shipping_method',
        'paymentModuleCode' => 'payment_code',
        'items' => 'Order\CustomerOrderItem',
        'billingAddress' => 'Order\CustomerOrderBillingAddress',
        'shippingAddress' => 'Order\CustomerOrderShippingAddress'
    ];
}