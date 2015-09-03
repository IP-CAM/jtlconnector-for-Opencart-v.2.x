<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\OpenCart\Controller\BaseController;

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
            FROM oc_product_option po
            LEFT JOIN oc_option o ON po.option_id = o.option_id
            WHERE po.product_id = %d AND o.type NOT IN ("checkbox", "file");',
            $data['product_id']
        );
    }

    public function pushData($data)
    {
        // TODO:
    }
}
