<?php

namespace jtl\Connector\OpenCart\Controller;

class CustomerOrderItem extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data);
        $result = $this->database->query($query);
        foreach ($result as $row) {
            $model = $this->mapper->toHost($row);
            $return[] = $model;
        }
        return $return;
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT ot.code, ot.value, op.*, p.sku
            FROM oc_order_total ot
            LEFT JOIN oc_order_product op ON op.order_id = ot.order_id AND ot.code = "sub_total"
            LEFT JOIN oc_product p ON p.product_id = op.product_id
            WHERE ot.order_id = %d AND ot.code != "total"',
            $data['order_id']
        );
    }
}