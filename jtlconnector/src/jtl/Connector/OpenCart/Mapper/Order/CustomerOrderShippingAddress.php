<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\BaseMapper;
use jtl\Connector\OpenCart\Utility\CustomField;

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
        return new Identity(CustomerOrder::SHIPPING_ID_PREFIX . $data['order_id']);
    }
}