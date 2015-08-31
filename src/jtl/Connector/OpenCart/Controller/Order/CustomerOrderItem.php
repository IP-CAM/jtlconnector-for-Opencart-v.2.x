<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\Order
 */

namespace jtl\Connector\OpenCart\Controller\Order;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Mapper\Order\OrderItemDiscountMapper;
use jtl\Connector\OpenCart\Mapper\Order\OrderItemProductMapper;
use jtl\Connector\OpenCart\Mapper\Order\OrderItemShippingMapper;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class CustomerOrderItem extends BaseController
{
    private $productMapper;
    private $shippingMapper;
    private $discountMapper;

    public function __construct()
    {
        parent::__construct();
        $this->productMapper = new OrderItemProductMapper();
        $this->shippingMapper = new OrderItemShippingMapper();
        $this->discountMapper = new OrderItemDiscountMapper();
    }

    public function pullData($data, $model, $limit = null)
    {
        $return = [];
        $orderItemId = 1;
        $orderId = $data['order_id'];
        $tax = doubleval($this->getTax($orderId));
        $products = $this->pullProducts($orderId);
        foreach ($products as $product) {
            $item = $this->productMapper->toHost($product);
            $item->setId(new Identity($orderId . '_' . $orderItemId++));
            $item->setVat($tax);
            $return[] = $item;
        }
        $products = $this->pullShippings($orderId);
        foreach ($products as $product) {
            $item = $this->shippingMapper->toHost($product);
            $item->setId(new Identity($orderId . '_' . $orderItemId++));
            $item->setVat($tax);
            $return[] = $item;
        }
        $products = $this->pullDiscounts($orderId);
        foreach ($products as $product) {
            $product['id'] = $orderId . '_' . $orderItemId++;
            $return[] = $this->discountMapper->toHost($product);
        }
        return $return;
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new OperationNotPermitedException("Use specific pull methods.");
    }

    private function pullProducts($orderId)
    {
        $query = sprintf('
            SELECT op.*, p.sku
            FROM oc_order_product op
            LEFT JOIN oc_product p ON p.product_id = op.product_id
            WHERE op.order_id = %d',
            $orderId
        );
        return $this->database->query($query);
    }

    private function pullShippings($orderId)
    {
        $query = sprintf('
            SELECT *
            FROM oc_order_total
            WHERE code = "shipping" AND order_id = %d',
            $orderId
        );
        return $this->database->query($query);
    }

    private function pullDiscounts($orderId)
    {
        $query = sprintf('
            SELECT *
            FROM oc_order_total
            WHERE code IN ("coupon", "voucher") AND order_id = %d',
            $orderId
        );
        return $this->database->query($query);
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