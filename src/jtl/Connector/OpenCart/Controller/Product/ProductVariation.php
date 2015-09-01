<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Core\Model\DataModel;
use jtl\Connector\OpenCart\Controller\BaseController;

class ProductVariation extends BaseController
{
    public function pullData(DataModel $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery(DataModel $data, $limit = null)
    {
        return sprintf('
            SELECT *
            FROM oc_product_option
            WHERE product_id = %d',
            $data['product_id']
        );
    }

    public function pushData(DataModel $data)
    {
        // TODO:
    }
}
