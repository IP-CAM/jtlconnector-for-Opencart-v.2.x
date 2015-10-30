<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\OpenCart\Mapper\BaseMapper;
use jtl\Connector\OpenCart\Utility\Constants;
use jtl\Connector\OpenCart\Utility\Db;
use jtl\Connector\Type\CustomerOrderItem;

abstract class OrderItemBaseMapper extends BaseMapper
{
    protected $pull = [
        'id' => 'order_item_id',
        'customerOrderId' => 'order_id',
        'name' => 'title',
        'price' => 'value',
        'type' => null,
        'quantity' => null
    ];

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        $this->database = DB::getInstance();
        $this->type = new CustomerOrderItem();
        $this->model = Constants::CORE_MODEL_NAMESPACE . 'CustomerOrderItem';
    }

    protected function quantity()
    {
        return 1.0;
    }
}