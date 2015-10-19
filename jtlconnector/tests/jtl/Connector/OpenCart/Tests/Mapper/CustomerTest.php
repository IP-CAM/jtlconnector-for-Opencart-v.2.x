<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use DateTime;
use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\Customer;

class CustomerTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new CustomerMock();
    }

    protected function getEndpoint()
    {
        return [
            'customer_id' => '1',
            'firstname' => 'Hannibal',
            'lastname' => 'Smith',
            'address_1' => 'In the black car',
            'address_2' => 'On the Road',
            'postcode' => '1234',
            'city' => 'Los Angels',
            'name' => 'California',
            'iso_code_2' => 'US',
            'company' => 'The A-Team',
            'email' => 'h.smith@ateam.com',
            'telephone' => '30823089',
            'fax' => '30823090',
            'customer_group_id' => '2',
            'date_added' => '2015-09-25',
            'newsletter' => false,
            'status' => true,
            'has_customer_account' => true
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\Customer();
        $result->setId(new Identity('1', 0));
        $result->setTitle('Dr.');
        $result->setSalutation('Herr');
        $result->setFirstName('Hannibal');
        $result->setLastName('Smith');
        $result->setStreet('In the black car');
        $result->setExtraAddressLine('On the Road');
        $result->setZipCode('1234');
        $result->setCity('Los Angels');
        $result->setState('California');
        $result->setCountryIso('US');
        $result->setCompany('The A-Team');
        $result->setVatNumber('97382978243');
        $result->setEMail('h.smith@ateam.com');
        $result->setPhone('30823089');
        $result->setFax('30823090');
        $result->setCustomerGroupId(new Identity('2', 0));
        $result->setCreationDate(new DateTime('2015-09-25'));
        $result->setHasNewsletterSubscription(false);
        $result->setIsActive(true);
        $result->setHasCustomerAccount(true);
        return $result;
    }

    public function testPush()
    {
    }
}

class CustomerMock extends Customer
{
    public function __construct()
    {
        parent::__construct(get_parent_class());
    }

    protected function vatNumber(array $data)
    {
        return '97382978243';
    }

    protected function title(array $data)
    {
        return 'Dr.';
    }

    protected function salutation(array $data)
    {
        return 'Herr';
    }
}