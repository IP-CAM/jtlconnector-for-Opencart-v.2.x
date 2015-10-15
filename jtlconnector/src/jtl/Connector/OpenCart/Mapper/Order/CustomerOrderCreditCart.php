<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Order
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class CustomerOrderCreditCart extends BaseMapper
{
    protected $pull = [
        'id' => 'card_id',
        'customerOrderId' => 'order_id',
        'creditCardExpiration' => 'expiry',
        'creditCardNumber' => 'digits',
        'creditCardType' => 'type'
    ];
}