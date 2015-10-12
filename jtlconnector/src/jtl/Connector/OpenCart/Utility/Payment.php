<?php

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Core\Utilities\Singleton;
use jtl\Connector\Payment\PaymentTypes;

class Payment extends Singleton
{
    private $paymentMapping = [
        'pp_express' => PaymentTypes::TYPE_PAYPAL_EXPRESS,
        'bank_transfer' => PaymentTypes::TYPE_BANK_TRANSFER,
        'cod' => PaymentTypes::TYPE_CASH_ON_DELIVERY,
        'cheque' => PaymentTypes::TYPE_CASH,
        'nochex' => PaymentTypes::TYPE_NOCHEX,
        'paymate' => PaymentTypes::TYPE_PAYMATE,
        'paypoint' => PaymentTypes::TYPE_PAYPOINT,
        'payza' => PaymentTypes::TYPE_PAYZA,
        'worldpay' => PaymentTypes::TYPE_WORLDPAY
    ];

    public function parseOpenCartPaymentCode($code)
    {
        if (isset($this->paymentMapping[$code])) {
            return $this->paymentMapping[$code];
        } else {
            if (strrpos('alipay', $code) !== false) {
                return PaymentTypes::TYPE_ALIPAY;
            }
            if (strrpos('bluepay', $code) !== false) {
                return PaymentTypes::TYPE_BPAY;
            }
            return '';
        }
    }

    /**
     * @return Payment
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }
}