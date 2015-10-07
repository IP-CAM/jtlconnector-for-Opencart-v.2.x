<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\Order
 */

namespace jtl\Connector\OpenCart\Controller\Order;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;

class CustomerOrderItemVariation extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        $return = parent::pullDataDefault($data, $model);
        foreach ($return as $row) {
            $row->setCustomerOrderItemId($model->getId());
        }
        return $return;
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::customerOrderItemVariation($data['order_item_id']);
    }
}