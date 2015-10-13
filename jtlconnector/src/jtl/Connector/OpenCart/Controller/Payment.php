<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
use jtl\Connector\OpenCart\Utility\Constants;
use jtl\Connector\Payment\PaymentTypes;

class Payment extends MainEntityController
{
    private $methods = [
        'paymentPaypalPull' => PaymentTypes::TYPE_PAYPAL_EXPRESS,
        'paymentWorldpayPull' => PaymentTypes::TYPE_WORLDPAY,
        'paymentBluepayRedirectPull' => PaymentTypes::TYPE_BPAY,
        'paymentBluepayHostedPull' => PaymentTypes::TYPE_BPAY
    ];

    /**
     * Add, as long as the limit is not exceeded, payments to the result by calling the abstract method for all the
     * different payment types.
     */
    public function pullData($data, $model, $limit = null)
    {
        $return = [];
        reset($this->methods);
        while (count($return) < $limit) {
            if ($this->addNextPayments($this->methods, $return, $limit) === false) {
                break;
            }
        }
        return $return;
    }

    /**
     * Call for each payment type the matching pull method and return if there is a type left.
     */
    private function addNextPayments(&$methods, &$return, &$limit)
    {
        list($method, $type) = each($methods);
        if (!is_null($method)) {
            $sqlMethod = Constants::UTILITY_NAMESPACE . 'SQLs::' . $method;
            $query = call_user_func($sqlMethod, $limit);
            $result = $this->database->query($query);
            foreach ($result as $picture) {
                $model = $this->mapper->toHost($picture);
                $return[] = $model;
            }
            return true;
        } else {
            return false;
        }
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new MethodNotAllowedException("Use the queries for the specific types.");
    }

    protected function getStats()
    {
        $return = [];
        $limit = PHP_INT_MAX;
        reset($this->methods);
        while ($this->addNextPayments($this->methods, $return, $limit) === true) {
        }
        return count($return);
    }
}
