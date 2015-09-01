<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Core\Model\DataModel;
use jtl\Connector\OpenCart\Controller\BaseController;

class ProductVarCombination extends BaseController
{
    public function pullData(DataModel $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery(DataModel $data, $limit = null)
    {
        return sprintf('
            SELECT *
            FROM oc_product_option_value
            WHERE product_id = %d',
            $data['product_id']
        );
    }
}
