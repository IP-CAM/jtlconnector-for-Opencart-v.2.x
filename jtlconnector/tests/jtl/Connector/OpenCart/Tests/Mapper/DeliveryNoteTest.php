<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use DateTime;
use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\DeliveryNote;

class DeliveryNoteTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new DeliveryNoteMock();
    }

    protected function getEndpoint()
    {
        return [
            'order_id' => 2,
            'date_added' => '2015-09-25 12:00:03'
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\DeliveryNote();
        $result->setId(new Identity('2', 0));
        $result->setCustomerOrderId(new Identity('2', 0));
        $result->setCreationDate(new DateTime('2015-09-25 12:00:03'));
        return $result;
    }

    public function testPush()
    {
    }
}

class DeliveryNoteMock extends DeliveryNote
{
    public function __construct()
    {
        parent::__construct(get_parent_class());
        unset($this->pull['items']);
    }
}