<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\OpenCart\Controller\BaseController;

class Product2Category extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT *, CONCAT(product_id, "_", category_id) as id
            FROM oc_product_to_category
            WHERE product_id = %d',
            $data['product_id']
        );
    }

    public function pushData($data, &$model)
    {
        foreach ($data->getCategories() as $category) {
            $model['product_category'][] = $category->getCategoryId()->getEndpoint();
        }
    }
}
