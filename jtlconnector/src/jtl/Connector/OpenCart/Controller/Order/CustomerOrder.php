<?php
/**
 * @author    Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller\Order;

use jtl\Connector\Model\CustomerOrder as CustomerOrderModel;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
use jtl\Connector\OpenCart\Mapper\Order\CustomerOrderCreditCart;
use jtl\Connector\OpenCart\Utility\SQLs;
use jtl\Connector\Payment\PaymentTypes;

class CustomerOrder extends MainEntityController
{
    private $shippingStatusMapping = [
        'Pending'    => CustomerOrderModel::STATUS_NEW,
        'Processing' => CustomerOrderModel::STATUS_PARTIALLY_SHIPPED,
        'Shipped'    => CustomerOrderModel::STATUS_SHIPPED,
        'Canceled'   => CustomerOrderModel::STATUS_CANCELLED
    ];

    public function pullData(array $data, $model, $limit = null)
    {
        $orders = parent::pullDataDefault($data, $limit);

        foreach ($orders as $order) {
            if ($order instanceof CustomerOrderModel) {
                $this->setShippingStatus($order);
                $this->setPaymentStatus($order);
                $this->setPaymentInfo($order);
            }
        }

        return $orders;
    }

    protected function pullQuery(array $data, $limit = null)
    {
        return SQLs::customerOrderPull($limit);
    }

    private function setShippingStatus(CustomerOrderModel &$order)
    {
        $shippingStatuses = [
            CustomerOrderModel::STATUS_NEW,
            CustomerOrderModel::STATUS_PARTIALLY_SHIPPED,
            CustomerOrderModel::STATUS_SHIPPED,
            CustomerOrderModel::STATUS_CANCELLED
        ];

        $result = $this->database->query(SQLs::customerOrderShippingStatus($order->getId()->getEndpoint()));

        if (!empty($result)) {
            if (in_array($result[0]['name'], $shippingStatuses)) {
                $order->setStatus($this->shippingStatusMapping[$result[0]['name']]);
            }

            $order->setShippingDate(date_create_from_format("Y-m-d H:i:s", $result[0]['date_added']));
        } else {
            $order->setStatus(CustomerOrderModel::STATUS_NEW);
        }
    }

    private function setPaymentStatus(CustomerOrderModel &$order)
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

        $query = SQLs::customerOrderPaymentStatus($order->getId()->getEndpoint(), implode($paymentStatus, ','));

        $result = $this->database->query($query);

        if (!empty($result)) {
            $order->setPaymentStatus(trim(str_replace('Payment:', '', $result[0]['comment'])));
            $order->setPaymentDate(date_create_from_format("Y-m-d H:i:s", $result[0]['date_added']));
        } else {
            $order->setStatus(CustomerOrderModel::PAYMENT_STATUS_UNPAID);
        }
    }

    private function setPaymentInfo(CustomerOrderModel $order)
    {
        $orderId = $order->getId()->getEndpoint();

        switch ($order->getPaymentModuleCode()) {
            case PaymentTypes::TYPE_BPAY:
                $paymentMapper = new  CustomerOrderCreditCart();
                $query = SQLs::paymentBluepayHostedCard($orderId);
                $result = $this->database->query($query);
                if ($result->num_rwos === 1) {
                    break;
                }
                $query = SQLs::paymentBluepayRedirectCard($orderId);
                $result = $this->database->query($query);
                break;
            case PaymentTypes::TYPE_WORLDPAY:
                $paymentMapper = new  CustomerOrderCreditCart();
                $query = SQLs::paymentWorldpayCard($orderId);
                $result = $this->database->query($query);
                break;
            default:
                return;
        }
        
        $paymentMapper->toHost($result);
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