<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\ProductVariationValue as PVVModel;
use jtl\Connector\OpenCart\Controller\BaseController;


class ProductVariationValueI18n extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT pov.product_option_value_id, ovd.name, l.code
            FROM oc_product_option_value pov
            LEFT JOIN oc_option_value_description ovd ON pov.option_value_id = ovd.option_value_id
            LEFT JOIN oc_language l ON ovd.language_id = l.language_id
            WHERE pov.product_option_value_id = %d',
            $data['product_option_value_id']
        );
    }

    public function pushData(PVVModel $data, &$model)
    {

    }
}
