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
use jtl\Connector\OpenCart\Utility\SQLs;

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
            $customerOrder = $this->database->queryOne(sprintf(SQLs::STATUS_CHANGE_BY_ORDER, $customerOrderId));
            if ($customerOrder !== null) {
                $statusId = $this->mapShippingStatus($data);
                $addHistory = sprintf(SQLs::STATUS_CHANGE_ADD, $customerOrderId, $statusId, $data->getPaymentStatus());
                $this->database->query($addHistory);
                $this->database->query(sprintf(SQLs::CUSTOMER_ORDER_STATUS, $statusId, $customerOrderId));
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
            $query = sprintf(SQLs::CUSTOMER_ORDER_SHIPPING_STATUS_ID, $data->getCustomerOrderId()->getEndpoint());
            return $this->database->queryOne($query);
        }
        return $status;
    }
}
