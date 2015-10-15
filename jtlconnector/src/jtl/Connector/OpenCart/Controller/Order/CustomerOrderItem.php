<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller\Order;

use jtl\Connector\Model\CustomerOrderItem as COI;
use jtl\Connector\Model\CustomerOrderItem as CustomerOrderItemModel;
use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
use jtl\Connector\OpenCart\Mapper\Order\OrderItemDiscountMapper;
use jtl\Connector\OpenCart\Mapper\Order\OrderItemProductMapper;
use jtl\Connector\OpenCart\Mapper\Order\OrderItemShippingMapper;
use jtl\Connector\OpenCart\Utility\Constants;
use jtl\Connector\OpenCart\Utility\SQLs;

class CustomerOrderItem extends BaseController
{
    private $tax;
    private $orderId;
    private $productMapper;
    private $shippingMapper;
    private $discountMapper;

    private $methods = [
        'customerOrderProducts' => COI::TYPE_PRODUCT,
        'customerOrderShippings' => COI::TYPE_SHIPPING,
        'customerOrderDiscounts' => COI::TYPE_DISCOUNT
    ];

    public function __construct()
    {
        parent::__construct();
        $this->productMapper = new OrderItemProductMapper();
        $this->shippingMapper = new OrderItemShippingMapper();
        $this->discountMapper = new OrderItemDiscountMapper();
    }

    public function pullData(array $data, $model, $limit = null)
    {
        $return = [];
        $orderItemId = 1;
        $this->orderId = $data['order_id'];
        $this->tax = doubleval($this->getTax($this->orderId));
        foreach ($this->methods as $method => $type) {
            $sqlMethod = Constants::UTILITY_NAMESPACE . 'SQLs::' . $method;
            $query = call_user_func($sqlMethod, $this->orderId);
            $items = $this->database->query($query);
            foreach ($items as $item) {
                $return[] = $this->mapItem($type, $item, $orderItemId++);
            }
        }
        return $return;
    }

    private function mapItem($type, $item, $id)
    {
        $item['order_item_id'] = $this->orderId . '_' . $id;
        $result = $this->{$type . 'Mapper'}->toHost($item);
        if ($result instanceof CustomerOrderItemModel) {
            $result->setId(new Identity($this->orderId . '_' . $id));
            if ($type != COI::TYPE_DISCOUNT) {
                $result->setVat($this->tax);
            }
        }
        return $result;
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new MethodNotAllowedException("Use the specific pull methods.");
    }

    private function getTax($orderId)
    {
        return $this->database->queryOne(SQLs::taxRateOfOrder($orderId));
    }
}