<?php
namespace jtl\Connector\OpenCart\Controller;

class ProductVariationI18n extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT po.product_option_id, od.name, l.code
            FROM oc_option_description od
            LEFT JOIN oc_product_option po ON po.option_id = od.option_id
            LEFT JOIN oc_language l ON l.language_id = od.language_id
            WHERE po.product_option_id = %d',
            $data['product_option_id']
        );
    }

    public function pushData($data)
    {
        // TODO:
    }
}
