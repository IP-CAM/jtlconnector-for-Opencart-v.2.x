<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Model\CrossSellingItem as CrossSellingItemModel;
use jtl\Connector\Model\Identity;

class CrossSellingItem extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        $query = $this->pullQuery($data, $limit);
        $result = $this->database->query($query);
        if (empty($result)) {
            return [];
        }
        $model = new CrossSellingItemModel();
        foreach ($result as $row) {
            $model->addProductId(new Identity($row['related_id']));
        }
        return [$model];
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT related_id
            FROM oc_product_related
            WHERE product_id = %d',
            $data['product_id']
        );
    }
}
