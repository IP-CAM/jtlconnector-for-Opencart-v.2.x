<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use DateTime;
use jtl\Connector\OpenCart\Mapper\Customer;

class CustomerTest extends AbstractMapper
{
    protected function getMapper()
    {
        return new Customer();
    }

    protected function getHost()
    {
        return [
            'id' => 1,
            'firstName' => 'Hannibal',
            'lastName' => 'Smith',
            'street' => 'In the black car',
            'extraAddressLine' => 'On the Road',
            'zipCode' => '1234',
            'city' => 'Los Angels',
            'state' => 'California',
            'countryIso' => 'US',
            'company' => 'The A-Team',
            'eMail' => 'h.smith@ateam.com',
            'phone' => '30823089',
            'fax' => '30823090',
            'customerGroupId' => 2,
            'creationDate' => new DateTime('2015-09-25'),
            'hasNewsletterSubscription' => false,
            'isActive' => true,
            'hasCustomerAccount' => true
        ];
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

    protected function assertToHost($result)
    {
        $this->assertEquals($this->host['id'], $result->getId()->getEndpoint());
        $this->assertEquals($this->host['customerGroupId'], $result->getCustomerGroupId()->getEndpoint());
        $this->assertEquals($this->host['city'], $result->getCity());
        $this->assertEquals($this->host['company'], $result->getCompany());
        $this->assertEquals($this->host['countryIso'], $result->getCountryIso());
        $this->assertEquals($this->host['creationDate'], $result->getCreationDate());
        $this->assertEquals($this->host['eMail'], $result->getEMail());
        $this->assertEquals($this->host['extraAddressLine'], $result->getExtraAddressLine());
        $this->assertEquals($this->host['fax'], $result->getFax());
        $this->assertEquals($this->host['firstName'], $result->getFirstName());
        $this->assertEquals($this->host['hasCustomerAccount'], $result->getHasCustomerAccount());
        $this->assertEquals($this->host['hasNewsletterSubscription'], $result->getHasNewsletterSubscription());
        $this->assertEquals($this->host['lastName'], $result->getLastName());
        $this->assertEquals($this->host['isActive'], $result->getIsActive());
        $this->assertEquals($this->host['phone'], $result->getPhone());
        $this->assertEquals($this->host['state'], $result->getState());
        $this->assertEquals($this->host['street'], $result->getStreet());
        $this->assertEquals($this->host['zipCode'], $result->getZipCode());
        // Default values
        $this->assertEquals(0.0, $result->getAccountCredit());
        $this->assertEquals(0.0, $result->getDiscount());
        $this->assertEmpty($result->getBirthday());
        $this->assertEmpty($result->getCustomerNumber());
        $this->assertEmpty($result->getDeliveryInstruction());
        $this->assertEmpty($result->getLanguageISO());
        $this->assertEmpty($result->getMobile());
        $this->assertEmpty($result->getOrigin());
        $this->assertEmpty($result->getSalutation());
        $this->assertEmpty($result->getVatNumber());
        $this->assertEmpty($result->getTitle());
        $this->assertEmpty($result->getWebsiteUrl());
        $this->assertEmpty($result->getAttributes());
    }
}
