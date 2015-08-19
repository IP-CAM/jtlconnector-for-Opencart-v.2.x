<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Mapper;

class CustomerOrder extends BaseMapper
{
    protected $pull = [
        'id' => 'order_id',
        'customerId' => 'customer_id',
        'billingAddress' => 'CustomerOrderBillingAddress',
        // Flat Shipping Rate?
        //'carrierName' => 'string',
        'creationDate' => 'date_added',
        'currencyIso' => 'string',
        'estimatedDeliveryDate' => 'DateTime',
        'languageISO' => 'string',
        'note' => 'string',
        'orderNumber' => 'string',
        'paymentDate' => 'DateTime',
        'paymentInfo' => 'CustomerOrderPaymentInfo',
        'paymentModuleCode' => 'string',
        'paymentStatus' => 'string',
        'shippingAddress' => 'CustomerOrderShippingAddress',
        'shippingDate' => 'DateTime',
        'shippingInfo' => 'string',
        'shippingMethodName' => 'string',
        'status' => 'string',
        'totalSum' => 'double',
        'attributes' => '\jtl\Connector\Model\CustomerOrderAttr',
        'items' => '\jtl\Connector\Model\CustomerOrderItem',
    ];

    protected $push = [

    ];
}