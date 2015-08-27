<?php
namespace jtl\Connector\OpenCart\Controller;

class ProductVariation extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT *
            FROM oc_product_option
            WHERE product_id = %d',
            $data['product_id']
        );
    }

    public function pushData($data)
    {
        // TODO:
    }
}
