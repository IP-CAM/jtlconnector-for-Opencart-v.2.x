<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Core\Model\DataModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use stdClass;

class Product2Category extends BaseController
{
    public function pullData(DataModel $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery(DataModel $data, $limit = null)
    {
        return sprintf('
            SELECT *
            FROM oc_product_to_category
            WHERE product_id = %d',
            $data['product_id']
        );
    }

    public function pushData(DataModel $data)
    {
        foreach ($data->getCategories() as $category) {
            $id = $category->getCategoryId()->getEndpoint();
            if (!empty($id)) {
                $catObj = new stdClass();
                $catObj->product_id = $data->getId()->getEndpoint();
                $catObj->ctaegory_id = $id;
                $this->database->insert($catObj, 'oc_product_to_category');
            }
        }
    }
}
