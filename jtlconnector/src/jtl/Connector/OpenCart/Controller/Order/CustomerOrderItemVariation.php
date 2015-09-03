<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\Order
 */

namespace jtl\Connector\OpenCart\Controller\Order;

use jtl\Connector\OpenCart\Controller\BaseController;

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
        return sprintf('
            SELECT oo.*, u.name AS filename, pov.price_prefix, pov.price
            FROM oc_order_option oo
            LEFT JOIN oc_product_option po ON oo.product_option_id = po.product_option_id
            LEFT JOIN oc_option o ON o.option_id = po.option_id
            LEFT JOIN oc_upload u ON u.code = oo.value
            LEFT JOIN oc_product_option_value pov ON pov.product_option_value_id = oo.product_option_value_id
            WHERE oo.order_id = %d
            ORDER BY o.sort_order',
            $data['order_item_id']
        );
    }
}