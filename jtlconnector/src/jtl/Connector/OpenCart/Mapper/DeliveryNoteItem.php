<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class DeliveryNoteItem extends BaseMapper
{
    protected $pull = [
        'id' => 'order_product_id',
        'customerOrderItemId' => 'order_product_id',
        'deliveryNoteId' => 'order_id',
        'quantity' => 'quantity'
    ];
}