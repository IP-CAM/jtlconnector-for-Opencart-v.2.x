<?php

namespace jtl\Connector\OpenCart\Controller;

class ProductAttr extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
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