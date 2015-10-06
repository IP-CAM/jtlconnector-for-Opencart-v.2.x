<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Order
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\BaseMapper;

class CustomerOrderShippingAddress extends BaseMapper
{
    protected $pull = [
        'id' => null,
        'customerId' => 'customer_id',
        'firstName' => 'shipping_firstname',
        'lastName' => 'shipping_lastname',
        'company' => 'shipping_company',
        'eMail' => 'email',
        'phone' => 'telephone',
        'fax' => 'fax',
        'street' => 'shipping_address_1',
        'extraAddressLine' => 'shipping_address_2',
        'zipCode' => 'shipping_postcode',
        'city' => 'shipping_city',
        'state' => 'shipping_zone',
        'countryIso' => 'iso_code_3'
    ];

    protected function id($data)
    {
        return new Identity(CustomerOrder::SHIPPING_ID_PREFIX . $data['order_id']);
    }
}