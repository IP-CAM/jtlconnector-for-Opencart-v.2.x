<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class Customer extends BaseMapper
{
    protected $pull = [
        'id' => 'customer_id',
        'firstName' => 'firstname',
        'lastName' => 'lastname',
        'street' => 'address_1',
        'extraAddressLine' => 'address_2',
        'zipCode' => 'postcode',
        'city' => 'city',
        'state' => 'name',
        'countryIso' => 'iso_code_2',
        'company' => 'company',
        'eMail' => 'email',
        'phone' => 'telephone',
        'fax' => 'fax',
        'customerGroupId' => 'customer_group_id',
        'creationDate' => 'date_added',
        'hasNewsletterSubscription' => 'newsletter',
        'isActive' => 'status',
        'hasCustomerAccount' => null
    ];

    protected $push = [
        'customer_id' => 'id',
        'firstname' => 'firstName',
        'lastname' => 'lastName',
        'address_1' => 'street',
        'address_2' => 'extraAddressLine',
        'postcode' => 'zipCode',
        'city' => 'city',
        'name' => 'state',
        'iso_code_2' => 'countryIso',
        'company' => 'company',
        'email' => 'eMail',
        'telephone' => 'phone',
        'fax' => 'fax',
        'customer_group_id' => 'customerGroupId',
        'date_added' => 'creationDate',
        'newsletter' => 'hasNewsletterSubscription',
        'status' => 'isActive',
        'has_customer_account' => 'hasCustomerAccount'
    ];

    protected function hasCustomerAccount($data)
    {
        return true;
    }

    protected function has_customer_account($data)
    {
        return true;
    }
}