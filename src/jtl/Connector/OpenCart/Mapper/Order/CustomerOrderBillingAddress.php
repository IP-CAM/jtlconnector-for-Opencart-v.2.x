<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Order
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\BaseMapper;

class CustomerOrderBillingAddress extends BaseMapper
{
    protected $pull = [
        'id' => null,
        'customerId' => 'customer_id',
        'firstName' => 'payment_firstname',
        'lastName' => 'payment_lastname',
        'company' => 'payment_company',
        'eMail' => 'email',
        'phone' => 'telephone',
        'fax' => 'fax',
        'street' => 'payment_address_1',
        'extraAddressLine' => 'payment_address_2',
        'zipCode' => 'payment_postcode',
        'city' => 'payment_city',
        'state' => 'payment_zone',
        'countryIso' => 'iso_code_3'
    ];

    protected function id($data)
    {
        return new Identity($data['order_id'] . CustomerOrder::BILLING_ID_SUFFIX);
    }
}