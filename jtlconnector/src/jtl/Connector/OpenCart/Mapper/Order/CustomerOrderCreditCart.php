<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class CustomerOrderCreditCart extends BaseMapper
{
    protected $pull = [
        'id' => 'card_id',
        'customerOrderId' => 'order_id',
        'creditCardType' => 'type',
        'creditCardNumber' => 'digits',
        'creditCardExpiration' => 'expiry'
    ];
}