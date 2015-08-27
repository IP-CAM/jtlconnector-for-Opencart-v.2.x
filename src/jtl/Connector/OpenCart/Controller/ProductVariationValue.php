<?php
namespace jtl\Connector\OpenCart\Controller;


class ProductVariationValue extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT *
            FROM oc_product_option_value
            WHERE product_option_id = %d',
            $data['product_option_id']
        );
    }

    public function pushData($data)
    {
        // TODO:
    }
}
