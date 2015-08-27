<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class CustomerOrder extends I18nBaseMapper
{
    protected $pull = [
        'id' => 'order_id',
        'customerId' => 'customer_id',
        'billingAddress' => 'CustomerOrderBillingAddress',
        'creationDate' => 'date_added',
        'currencyIso' => 'currency_code',
        'languageISO' => null,
        'note' => 'comment',
        'shippingAddress' => 'CustomerOrderShippingAddress',
        'totalSum' => 'total',
        // TODO: Error
        'items' => 'CustomerOrderItem',
        // TODO: invoice_no und invoice_prefix
        // 'order_no' => '',
        // History ?
        //'paymentDate' => 'DateTime',
        // See PaymentTypes ?
        //'paymentModuleCode' => 'string',
        //'paymentStatus' => 'string',
        //'shippingDate' => 'DateTime',
        'shippingMethodName' => 'shipping_method',
        // Shipping: const in Custom Order
        //'status' => 'string',
    ];
}