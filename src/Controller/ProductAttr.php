<?php

namespace jtl\Connector\OpenCart\Controller;

class ProductAttr extends BaseController
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
            SELECT *
            FROM oc_product_attribute
            WHERE product_id = %d',
            $data['product_id']
        );
    }
}