<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

class DeliveryNoteItem extends BaseMapper
{
    protected $pull = [
        'id' => 'order_product_id',
        'deliveryNoteId' => 'order_id',
        'customerOrderItemId' => 'order_product_id',
        'quantity' => 'quantity'
    ];
}