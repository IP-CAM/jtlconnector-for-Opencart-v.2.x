<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\Order
 */

namespace jtl\Connector\OpenCart\Controller\Order;

use jtl\Connector\Model\CustomerOrder as CustomerOrderModel;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
use jtl\Connector\OpenCart\Utility\SQLs;

class CustomerOrder extends MainEntityController
{
    private $shippingStatusMapping = [
        'Pending' => CustomerOrderModel::STATUS_NEW,
        'Processing' => CustomerOrderModel::STATUS_PARTIALLY_SHIPPED,
        'Shipped' => CustomerOrderModel::STATUS_SHIPPED,
        'Canceled' => CustomerOrderModel::STATUS_CANCELLED
    ];

    public function pullData($data, $model, $limit = null)
    {
        $orders = parent::pullDataDefault($data, $limit);
        foreach ($orders as $order) {
            if ($order instanceof CustomerOrderModel) {
                $id = $order->getId()->getEndpoint();
                $this->setShippingStatus($id, $order);
                $this->setPaymentStatus($id, $order);
            }
        }
        return $orders;
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::customerOrderPull($limit);
    }

    private function setShippingStatus($id, CustomerOrderModel &$order)
    {
        $shippingStatuses = [
            CustomerOrderModel::STATUS_NEW,
            CustomerOrderModel::STATUS_PARTIALLY_SHIPPED,
            CustomerOrderModel::STATUS_SHIPPED,
            CustomerOrderModel::STATUS_CANCELLED
        ];
        $result = $this->database->query(SQLs::customerOrderShippingStatus($id));
        if (!empty($result)) {
            if (in_array($result[0]['name'], $shippingStatuses)) {
                $order->setStatus($this->shippingStatusMapping[$result[0]['name']]);
            }
            $order->setShippingDate(date_create_from_format("Y-m-d H:i:s", $result[0]['date_added']));
        }
    }

    private function setPaymentStatus($id, CustomerOrderModel &$order)
    {
        $paymentStatus = [];
        $paymentStatuses = [
            CustomerOrderModel::PAYMENT_STATUS_UNPAID,
            CustomerOrderModel::PAYMENT_STATUS_PARTIALLY,
            CustomerOrderModel::PAYMENT_STATUS_COMPLETED
        ];
        foreach ($paymentStatuses as $status) {
            $paymentStatus[] = "'{$status}'";
        }
        $query = SQLs::customerOrderPaymentStatus($id, implode($paymentStatus, ','));
        $result = $this->database->query($query);
        if (!empty($result)) {
            $order->setPaymentStatus(trim(str_replace('Payment:', '', $result[0]['comment'])));
            $order->setPaymentDate(date_create_from_format("Y-m-d H:i:s", $result[0]['date_added']));
        }
    }

    protected function pushData(CustomerOrderModel $data, $model)
    {
        throw new MethodNotAllowedException();
    }

    protected function deleteData(CustomerOrderModel $data)
    {
        throw new MethodNotAllowedException();
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::customerOrderStats());
    }
}