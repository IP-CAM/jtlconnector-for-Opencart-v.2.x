<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Payment\PaymentTypes;

class Payment
{
    private static $paymentMapping = [
        'pp_express' => PaymentTypes::TYPE_PAYPAL_EXPRESS,
        'bank_transfer' => PaymentTypes::TYPE_BANK_TRANSFER,
        'cod' => PaymentTypes::TYPE_CASH_ON_DELIVERY,
        'cheque' => PaymentTypes::TYPE_CASH,
        'nochex' => PaymentTypes::TYPE_NOCHEX,
        'paymate' => PaymentTypes::TYPE_PAYMATE,
        'paypoint' => PaymentTypes::TYPE_PAYPOINT,
        'payza' => PaymentTypes::TYPE_PAYZA,
        'skrill' => PaymentTypes::TYPE_SKRILL,
        'worldpay' => PaymentTypes::TYPE_WORLDPAY
    ];

    public static function parseOpenCartPaymentCode($code)
    {
        if (isset(self::$paymentMapping[$code])) {
            return self::$paymentMapping[$code];
        }

        if (strrpos('alipay', $code) !== false) {
            return PaymentTypes::TYPE_ALIPAY;
        }

        if (strrpos('bluepay', $code) !== false) {
            return PaymentTypes::TYPE_BPAY;
        }

        return $code;
    }
}