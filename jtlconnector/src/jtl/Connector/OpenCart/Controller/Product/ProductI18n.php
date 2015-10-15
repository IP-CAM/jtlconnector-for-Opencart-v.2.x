<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;

class ProductI18n extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::productI18nPull($data['product_id']);
    }

    public function pushData(ProductModel $data, &$model)
    {
        parent::pushDataI18n($data, $model, 'product_description');
    }
}