<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class DeliveryNote extends BaseMapper
{
    protected $pull = [
        'id' => 'order_id',
        'customerOrderId' => 'order_id',
        'creationDate' => 'date_added',
        'items' => 'DeliveryNoteItem'
    ];
}