<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\OpenCart\Utility\Db;
use jtl\Connector\OpenCart\Utility\SQLs;

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
        'hasCustomerAccount' => null,
        'vatNumber' => null,
        'title' => null,
        'salutation' => null
    ];

    public function __construct()
    {
        parent::__construct();
        $this->database = Db::getInstance();
    }


    protected function vatNumber(array $data)
    {
        $valueId = $this->database->queryOne(SQLs::freeFieldVatId());
        if (!is_null($valueId)) {
            return json_decode($data['custom_field'])->$valueId;
        }
        return "";
    }

    protected function title(array $data)
    {
        $freeFieldId = $this->database->queryOne(SQLs::freeFieldTitleId());
        if (!is_null($freeFieldId)) {
            $valueId = json_decode($data['custom_field'])->$freeFieldId;
            return $this->database->queryOne(SQLs::freeFieldValue($valueId));
        }
        return "";
    }

    protected function salutation(array $data)
    {
        $freeFieldId = $this->database->queryOne(SQLs::freeFieldSalutationId());
        if (!is_null($freeFieldId)) {
            $valueId = json_decode($data['custom_field'])->$freeFieldId;
            return $this->database->queryOne(SQLs::freeFieldValue($valueId));
        }
        return "";
    }

    protected function hasCustomerAccount()
    {
        return true;
    }
}