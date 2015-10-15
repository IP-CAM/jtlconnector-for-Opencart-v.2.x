<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\OpenCart\Utility\Payment as PaymentHelper;

class Payment extends BaseMapper
{
    protected $pull = [
        'id' => 'id',
        'customerOrderId' => 'order_id',
        'creationDate' => 'date_added',
        'totalSum' => 'amount',
        'billingInfo' => null,
        'transactionId' => null,
        'paymentModuleCode' => null,
    ];

    protected function paymentModuleCode(array $data)
    {
        return PaymentHelper::parseOpenCartPaymentCode($data['payment_code']);
    }

    protected function billingInfo(array $data)
    {
        return isset($data['note']) ? $data['note'] : '';
    }

    protected function transactionId(array $data)
    {
        return isset($data['transaction_id']) ? $data['transaction_id'] : '';
    }
}