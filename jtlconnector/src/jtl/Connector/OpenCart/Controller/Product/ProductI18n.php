<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;

class ProductI18n extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf(SQLs::PRODUCT_I18N_PULL, $data['product_id']);
    }

    public function pushData($data, &$model)
    {
        parent::pushDataI18n($data, $model, 'product_description');
    }
}