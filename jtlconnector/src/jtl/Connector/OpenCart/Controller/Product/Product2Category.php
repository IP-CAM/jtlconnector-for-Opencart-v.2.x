<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;

class Product2Category extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::productCategoryPull($data['product_id']);
    }

    public function pushData(ProductModel $data, &$model)
    {
        foreach ((array)$data->getCategories() as $category) {
            $model['product_category'][] = $category->getCategoryId()->getEndpoint();
        }
    }
}