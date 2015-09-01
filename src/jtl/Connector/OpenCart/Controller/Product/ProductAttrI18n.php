<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Core\Model\DataModel;
use jtl\Connector\OpenCart\Controller\BaseController;

class ProductAttrI18n extends BaseController
{
    public function pullData(DataModel $data, $model, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data);
        $result = $this->database->query($query);
        foreach ($result as $row) {
            $row['value'] = $data['text'];
            $model = $this->mapper->toHost($row);
            $return[] = $model;
        }
        return $return;
    }

    protected function pullQuery(DataModel $data, $limit = null)
    {
        return sprintf('
            SELECT ad.name, l.code, ad.attribute_id
            FROM oc_attribute_description ad
            LEFT JOIN oc_language l ON ad.language_id = l.language_id
            WHERE ad.attribute_id = %d',
            $data['attribute_id']
        );
    }
}