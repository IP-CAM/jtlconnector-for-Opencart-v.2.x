<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\ProductVariation as ProductVariationModel;
use jtl\Connector\OpenCart\Controller\BaseController;

class ProductVariationValue extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
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

    public function pushData(ProductVariationModel $data, &$model)
    {
        foreach ($data->getValues() as $value) {
            var_dump($value);
            var_dump($this->mapper->toEndpoint($value));
            die();
            $model[] = $this->mapper->toEndpoint($value);
        }
    }
}
