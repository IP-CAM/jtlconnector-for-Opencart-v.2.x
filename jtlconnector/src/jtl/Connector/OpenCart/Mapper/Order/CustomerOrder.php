<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;
use jtl\Connector\OpenCart\Utility\Payment as PaymentHelper;

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
        'totalSum' => 'total_sum',
        'shippingMethodName' => 'shipping_method',
        'paymentModuleCode' => null,
        'items' => 'Order\CustomerOrderItem',
        'billingAddress' => 'Order\CustomerOrderBillingAddress',
        'shippingAddress' => 'Order\CustomerOrderShippingAddress'
    ];

    protected function paymentModuleCode(array $data)
    {
        return PaymentHelper::parseOpenCartPaymentCode($data['payment_code']);
    }
}