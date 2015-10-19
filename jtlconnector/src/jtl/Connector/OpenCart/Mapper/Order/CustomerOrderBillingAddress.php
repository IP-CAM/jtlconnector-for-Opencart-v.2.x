<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\BaseMapper;
use jtl\Connector\OpenCart\Utility\CustomField;

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
        'countryIso' => 'iso_code_3',
        'vatNumber' => null,
        'title' => null,
        'salutation' => null
    ];

    protected function vatNumber(array $data)
    {
        return CustomField::getInstance()->vatNumber($data);
    }

    protected function title(array $data)
    {
        return CustomField::getInstance()->title($data);
    }

    protected function salutation(array $data)
    {
        return CustomField::getInstance()->salutation($data);
    }

    protected function id($data)
    {
        return new Identity(CustomerOrder::BILLING_ID_PREFIX . $data['order_id']);
    }
}