<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\DeliveryNoteItem;

class DeliveryNoteItemTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new DeliveryNoteItem();
    }

    protected function getEndpoint()
    {
        return [
            'order_product_id' => 1,
            'order_id' => 2,
            'quantity' => 5
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\DeliveryNoteItem();
        $result->setId(new Identity('1', 0));
        $result->setCustomerOrderItemId(new Identity('1', 0));
        $result->setDeliveryNoteId(new Identity('2', 0));
        $result->setQuantity(5.0);
        return $result;
    }

    public function testPush()
    {
    }
}