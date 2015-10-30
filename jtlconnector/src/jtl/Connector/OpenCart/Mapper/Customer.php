<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\OpenCart\Utility\CustomField;

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
        'state' => 'state',
        'countryIso' => 'country_iso',
        'company' => 'company',
        'eMail' => 'email',
        'phone' => 'telephone',
        'fax' => 'fax',
        'customerGroupId' => 'customer_group_id',
        'creationDate' => 'date_added',
        'hasNewsletterSubscription' => 'newsletter',
        'isActive' => 'status',
        'title' => null,
        'vatNumber' => null,
        'salutation' => null,
        'hasCustomerAccount' => null
    ];

    protected function title(array $data)
    {
        return CustomField::getInstance()->title($data);
    }

    protected function vatNumber(array $data)
    {
        return CustomField::getInstance()->vatNumber($data);
    }

    protected function salutation(array $data)
    {
        return CustomField::getInstance()->salutation($data);
    }

    protected function hasCustomerAccount()
    {
        return true;
    }
}