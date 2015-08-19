<?php

namespace jtl\Connector\OpenCart\Mapper;

class CustomerOrderBillingAddress extends BaseMapper
{
    protected $pull = [
        'customerId' => 'customer_id',
        // Gibbet nicht
        //'id' => 'Identity',
        'firstName' => 'payment_firstname',
        'lastName' => 'payment_lastname',
        'company' => 'payment_company',
        // Maybe payment_custom_field or shipping_custom_field
        //'deliveryInstruction' => 'string',
        // The same as customer or order or null
        //'mobile' => 'string',
        //'phone' => 'string',
        //'fax' => 'string',
        //'eMail' => 'string',
        'street' => 'payment_address_1',
        'extraAddressLine' => 'payment_address_2',
        'zipCode' => 'payment_postcode',
        'city' => 'payment_city',
        'state' => 'payment_zone',
        'countryIso' => 'iso_code_3'
    ];
}