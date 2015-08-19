<?php

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class CustomerOrderItem extends BaseMapper
{
    public function pullData($data, $model, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data);
        var_dump($query);
        $result = $this->db->query($query);
        foreach ($result as $row) {
            $model = $this->mapper->toHost($row);
            $return[] = $model;
        }
        return $return;
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT op.*, p.sku, ot.code
            FROM oc_order_product op
            LEFT JOIN oc_product p ON op.product_id = p.product_id
            LEFT JOIN oc_order_total ot ON op.order_id = ot.order_id
            WHERE op.order_id = %d',
            $data['order_id']
        );
    }
}