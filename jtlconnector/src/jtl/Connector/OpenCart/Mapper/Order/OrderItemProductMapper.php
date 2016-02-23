<?php
/**
 * @author    Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\Model\CustomerOrderItem as CustomerOrderItemModel;
use jtl\Connector\OpenCart\Mapper\BaseMapper;
use jtl\Connector\OpenCart\Utility\Constants;
use jtl\Connector\OpenCart\Utility\Db;
use jtl\Connector\Type\CustomerOrderItem;

class OrderItemProductMapper extends BaseMapper
{
    protected $pull = [
        'id'              => 'order_item_id',
        'productId'       => 'product_id',
        'customerOrderId' => 'order_id',
        'sku'             => 'sku',
        'name'            => 'name',
        'quantity'        => 'quantity',
        'variations'      => 'Order\CustomerOrderItemVariation',
        'type'            => null,
        'price'           => null,
        'priceGross'      => null,
        'vat'             => null
    ];

    public function __construct()
    {
        $this->database = DB::getInstance();
        $this->type = new CustomerOrderItem();
        $this->model = Constants::CORE_MODEL_NAMESPACE . 'CustomerOrderItem';
    }

    protected function type()
    {
        return CustomerOrderItemModel::TYPE_PRODUCT;
    }

    protected function price(array $item)
    {
        return round((float)$item['price'], 2);
    }

    protected function priceGross(array $item)
    {
        return round((float)$item['price'] + (float)$item['tax'], 2);
    }

    protected function vat(array $item)
    {
        return round((float)$item['tax'] / (float)$item['price'] * 100, 2);
    }
}