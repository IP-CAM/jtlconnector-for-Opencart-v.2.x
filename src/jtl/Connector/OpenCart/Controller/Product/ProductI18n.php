<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Core\Model\DataModel;
use jtl\Connector\OpenCart\Controller\BaseController;

class ProductI18n extends BaseController
{
    public function pullData(DataModel $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery(DataModel $data, $limit = null)
    {
        return sprintf('
            SELECT p.*, l.code
            FROM oc_product_description p
            LEFT JOIN oc_language l ON p.language_id = l.language_id
            WHERE p.product_id = %d',
            $data['product_id']
        );
    }
}