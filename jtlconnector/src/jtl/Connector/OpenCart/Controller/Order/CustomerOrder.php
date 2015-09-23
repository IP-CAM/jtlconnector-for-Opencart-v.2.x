<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\Order
 */

namespace jtl\Connector\OpenCart\Controller\Order;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\Model\CustomerOrder as CustomerOrderModel;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;

class CustomerOrder extends MainEntityController
{
    const PAYMENT_STATUS = [
        CustomerOrderModel::PAYMENT_STATUS_UNPAID,
        CustomerOrderModel::PAYMENT_STATUS_PARTIALLY,
        CustomerOrderModel::PAYMENT_STATUS_COMPLETED
    ];
    const SHIPPING_STATUS = [
        CustomerOrderModel::STATUS_NEW,
        CustomerOrderModel::STATUS_PARTIALLY_SHIPPED,
        CustomerOrderModel::STATUS_SHIPPED,
        CustomerOrderModel::STATUS_CANCELLED
    ];
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
            $id = $order->getId()->getEndpoint();
            $this->setShippingStatus($id, $order);
            $this->setPaymentStatus($id, $order);
        }
        return $orders;
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT o.*, l.code, c.iso_code_3
            FROM oc_order o
            LEFT JOIN oc_language l ON o.language_id = l.language_id
            LEFT JOIN oc_country c ON o.payment_country_id = c.country_id
            LEFT JOIN jtl_connector_link cl ON o.order_id = cl.endpointId AND cl.type = %d
            WHERE cl.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_CUSTOMER_ORDER, $limit
        );
    }

    protected function getStats()
    {
        return $this->database->queryOne(sprintf('
			SELECT COUNT(*)
			FROM oc_order o
			LEFT JOIN jtl_connector_link l ON o.order_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_CUSTOMER_ORDER
        ));
    }

    protected function pushData($data, $model)
    {
        throw new MethodNotAllowedException();
    }

    protected function deleteData($data)
    {
        throw new MethodNotAllowedException();
    }

    private function setShippingStatus($id, CustomerOrderModel &$order)
    {
        $result = $this->database->query(sprintf('
            SELECT os.name, oh.date_added
            FROM oc_order o
            LEFT JOIN oc_language l ON o.language_id = l.language_id
            LEFT JOIN oc_order_status os ON os.order_status_id = o.order_status_id AND os.language_id = l.language_id
            LEFT JOIN oc_order_history oh ON oh.order_status_id = o.order_status_id
            WHERE oh.order_id = %d
            ORDER BY oh.date_added
            LIMIT 1',
            $id
        ));
        if (!empty($result)) {
            if (in_array($result[0]['name'], self::SHIPPING_STATUS)) {
                $order->setStatus($this->shippingStatusMapping[$result[0]['name']]);
            }
            $order->setShippingDate(date_create_from_format("Y-m-d H:i:s", $result[0]['date_added']));
        }
    }

    private function setPaymentStatus($id, CustomerOrderModel &$order)
    {
        $paymentStatus = [];
        foreach (self::PAYMENT_STATUS as $status) {
            $paymentStatus[] = "'{$status}'";
        }
        $result = $this->database->query(sprintf('
            SELECT oh.comment, oh.date_added
            FROM oc_order o
            LEFT JOIN oc_language l ON o.language_id = l.language_id
            LEFT JOIN oc_order_history oh ON oh.order_status_id = o.order_status_id
            WHERE oh.order_id = %d AND oh.comment != "" AND oh.comment IN (%s)
            ORDER BY oh.date_added
            LIMIT 1',
            $id, implode($paymentStatus, ',')
        ));
        if (!empty($result)) {
            $order->setPaymentStatus(trim(str_replace('Payment:', '', $result[0]['comment'])));
            $order->setPaymentDate(date_create_from_format("Y-m-d H:i:s", $result[0]['date_added']));
        }
    }
}
