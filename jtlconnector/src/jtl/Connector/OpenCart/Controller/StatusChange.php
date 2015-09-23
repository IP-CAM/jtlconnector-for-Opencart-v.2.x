<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Core\Exception\DatabaseException;
use jtl\Connector\Model\CustomerOrder;
use jtl\Connector\Model\StatusChange as StatusChangeModel;
use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
use jtl\Connector\OpenCart\Utility\OpenCart;

class StatusChange extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        throw new MethodNotAllowedException();
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new MethodNotAllowedException();
    }

    public function pushData(StatusChangeModel $data, $model)
    {
        $customerOrderId = $data->getCustomerOrderId()->getEndpoint();
        if (!empty($customerOrderId)) {
            $customerOrder = $this->database->queryOne(sprintf('
                SELECT count(*) FROM oc_order WHERE order_id = %d', $customerOrderId
            ));
            if ($customerOrder !== null) {
                $statusId = $this->mapShippingStatus($data);
                $this->database->query(sprintf('
                    INSERT INTO oc_order_history (order_id, order_status_id, notify, comment, date_added)
                    VALUES (%d, %d, %d, "Payment: %s", NOW())',
                    $customerOrderId, $statusId, 0, $data->getPaymentStatus()
                ));
                $this->database->query(sprintf('
                    UPDATE oc_order
                    SET order_status_id = %d, date_modified = NOW()
                    WHERE order_id = %d',
                    $statusId, $customerOrderId
                ));
                return $data;
            }
            throw new DatabaseException(sprintf('Customer Order with Endpoint Id (%s) cannot be found',
                $customerOrderId));
        }
        throw new DatabaseException('Customer Order Endpoint Id cannot be empty');
    }

    private function mapShippingStatus(StatusChangeModel $data)
    {
        $status = null;
        switch ($data->getOrderStatus()) {
            case CustomerOrder::STATUS_NEW:
                $status = 1;
                break;
            case CustomerOrder::STATUS_CANCELLED:
                $status = 7;
                break;
            case CustomerOrder::STATUS_PARTIALLY_SHIPPED:
                $status = 2;
                break;
            case CustomerOrder::STATUS_SHIPPED:
                $status = 3;
                break;
        }
        if (is_null($status)) {
            return $this->database->queryOne('
                SELECT oh.order_status_id
                FROM oc_order_status os
                LEFT JOIN oc_order_history oh ON oh.order_status_id = os.order_status_id
                WHERE oh.order_id = %d
                ORDER BY oh.date_added
                LIMIT 1',
                $data->getCustomerOrderId()->getEndpoint()
            );
        }
        return $status;
    }
}
