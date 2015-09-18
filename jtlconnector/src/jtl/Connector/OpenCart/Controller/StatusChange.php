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
            $ocOrder = OpenCart::getInstance()->loadModel('checkout/oder');
            $customerOrder = $ocOrder->getOrder($customerOrderId);
            if ($customerOrder !== null) {
                $statusId = $this->mapOrderStatus($data);
                $this->database->query(sprintf('
                    INSERT INTO oc_order_history (order_id, order_status_id, notify, comment, date_added)
                    VALUES (%d, %d, %d, %s, NOW())',
                    $customerOrderId, $statusId, 0, ''
                ));
                $this->database->query(sprintf('
                    INSERT INTO oc_order_history (order_id, order_status_id, notify, comment, date_added)
                    VALUES (%d, %d, %d, %s, NOW())',
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

    private function mapOrderStatus(StatusChangeModel $data)
    {
        $status = null;
        switch ($data->getOrderStatus()) {
            case CustomerOrder::STATUS_NEW:
                $status = 'Pending';
                break;
            case CustomerOrder::STATUS_CANCELLED:
                $status = 'Canceled';
                break;
            case CustomerOrder::STATUS_PARTIALLY_SHIPPED:
                $status = 'Processing';
                break;
            case CustomerOrder::STATUS_SHIPPED:
                $status = 'Shipped';
                break;
        }
        $status = $this->database->queryOne(sprintf('
            SELECT os.order_status_id
            FROM oc_settings s
            LEFT JOIN oc_language l ON l.code = s.config_admin_language
            LEFT JOIN oc_order_status os ON os.language_id = l.language_id
            WHERE os.name = %s',
            $status
        ));
        return $status;
    }
}
