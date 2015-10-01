<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Core\Logger\Logger;
use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\Model\ProductAttr as ProductAttribute;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;
use jtl\Connector\OpenCart\Utility\Utils;

class ProductSpecific extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf(SQLs::PRODUCT_SPECIFIC_PULL, $data['product_id']);
    }

    public function pushData(ProductModel $data, &$model)
    {
        $model['product_filter'] = [];
        foreach ((array)$data->getSpecifics() as $specific) {
            if (!is_null($specific->getSpecificValueId()->getEndpoint())) {
                $filterId = $specific->getSpecificValueId()->getEndpoint();
                $model['product_filter'][] = $filterId;
                foreach ($data->getCategories() as $category) {
                    $categoryId = $category->getCategoryId()->getEndpoint();
                    $query = sprintf(SQLs::PRODUCT_SPECIFIC_CATEGORY_FIND, $categoryId, $filterId);
                    if ($this->database->queryOne($query) === 0) {
                        $this->database->query(sprintf(SQLs::PRODUCT_SPECIFIC_CATEGORY_ADD, $categoryId, $filterId));
                    }
                }
            }
        }
    }
}