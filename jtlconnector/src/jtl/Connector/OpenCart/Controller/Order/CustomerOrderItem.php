<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\Order
 */

namespace jtl\Connector\OpenCart\Controller\Order;

use jtl\Connector\Model\CustomerOrderItem as COI;
use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Mapper\Order\OrderItemDiscountMapper;
use jtl\Connector\OpenCart\Mapper\Order\OrderItemProductMapper;
use jtl\Connector\OpenCart\Mapper\Order\OrderItemShippingMapper;
use jtl\Connector\OpenCart\Utility\SQLs;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class CustomerOrderItem extends BaseController
{
    const TYPE_METHODS = [COI::TYPE_PRODUCT, COI::TYPE_SHIPPING, COI::TYPE_DISCOUNT];

    private $productMapper;
    private $shippingMapper;
    private $discountMapper;
    private $orderId;
    private $tax;

    public function __construct()
    {
        parent::__construct();
        $this->productMapper = new OrderItemProductMapper();
        $this->shippingMapper = new OrderItemShippingMapper();
        $this->discountMapper = new OrderItemDiscountMapper();
    }

    public function pullData($data, $model, $limit = null)
    {
        $this->orderId = $data['order_id'];
        $this->tax = doubleval($this->getTax($this->orderId));
        $return = [];
        $orderItemId = 1;
        foreach (self::TYPE_METHODS as $type) {
            $items = $this->{'pull' . ucfirst($type) . 's'}($this->orderId);
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
        $result->setId(new Identity($this->orderId . '_' . $id));
        if ($type != COI::TYPE_DISCOUNT) {
            $result->setVat($this->tax);
        }
        return $result;
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new OperationNotPermitedException("Use specific pull methods.");
    }

    private function pullProducts($orderId)
    {
        return $this->database->query(SQLs::CUSTOMER_ORDER_PRODUCTS, $orderId);
    }

    private function pullShippings($orderId)
    {
        return $this->database->query(SQLs::CUSTOMER_ORDER_SHIPPINGS, $orderId);
    }

    private function pullDiscounts($orderId)
    {
        return $this->database->query(SQLs::CUSTOMER_ORDER_DISCOUNTS,$orderId);
    }

    private function getTax($orderId)
    {
        $query = sprintf('
            SELECT tr.rate
            FROM oc_order_total ot
            LEFT JOIN oc_tax_rate tr ON tr.name = ot.title
            WHERE ot.code = "tax" AND ot.order_id = %d',
            $orderId
        );
        return $this->database->queryOne($query);
    }
}