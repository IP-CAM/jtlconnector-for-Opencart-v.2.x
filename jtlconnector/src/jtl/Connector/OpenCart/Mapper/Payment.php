<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\OpenCart\Utility\Payment as PaymentHelper;

class Payment extends BaseMapper
{
    protected $pull = [
        'id' => 'id',
        'customerOrderId' => 'order_id',
        'billingInfo' => 'note',
        'creationDate' => 'date_added',
        'paymentModuleCode' => null,
        'totalSum' => 'total',
        'transactionId' => 'transaction_id'
    ];

    protected function paymentModuleCode(array $data)
    {
        return PaymentHelper::parseOpenCartPaymentCode($data['payment_code']);
    }
}