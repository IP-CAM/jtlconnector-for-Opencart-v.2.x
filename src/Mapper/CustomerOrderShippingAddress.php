<?php

namespace jtl\Connector\OpenCart\Mapper;

class CustomerOrderShippingAddress extends BaseMapper
{
    protected $pull = [
        'customerId' => 'customer_id',
        // Gibbet nicht
        //'id' => 'Identity',
        'firstName' => 'shipping_firstname',
        'lastName' => 'shipping_lastname',
        'company' => 'shipping_company',
        // Maybe shipping_custom_field or shipping_custom_field
        //'deliveryInstruction' => 'string',
        // The same as customer or order or null
        //'mobile' => 'string',
        //'phone' => 'string',
        //'fax' => 'string',
        //'eMail' => 'string',
        'street' => 'shipping_address_1',
        'extraAddressLine' => 'shipping_address_2',
        'zipCode' => 'shipping_postcode',
        'city' => 'shipping_city',
        'state' => 'shipping_zone',
        'countryIso' => 'iso_code_3'
    ];
}