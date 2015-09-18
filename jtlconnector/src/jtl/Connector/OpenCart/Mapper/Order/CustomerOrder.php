<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Order
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class CustomerOrder extends I18nBaseMapper
{
    const SHIPPING_ID_SUFFIX = "_s";
    const BILLING_ID_SUFFIX = "_b";

    protected $pull = [
        'id' => 'order_id',
        'orderNumber' => 'order_id',
        'customerId' => 'customer_id',
        'billingAddress' => 'Order\CustomerOrderBillingAddress',
        'creationDate' => 'date_added',
        'currencyIso' => 'currency_code',
        'languageISO' => null,
        'note' => 'comment',
        'shippingAddress' => 'Order\CustomerOrderShippingAddress',
        'totalSum' => 'total',
        'items' => 'Order\CustomerOrderItem',
        'shippingMethodName' => 'shipping_method'
    ];
}