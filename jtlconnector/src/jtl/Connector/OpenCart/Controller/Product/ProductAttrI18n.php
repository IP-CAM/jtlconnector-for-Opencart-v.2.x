<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Exceptions\DataAlreadyFetchedException;
use jtl\Connector\OpenCart\Utility\SQLs;

class ProductAttrI18n extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data, $limit);
        $result = $this->database->query($query);
        foreach ((array)$result as $row) {
            $row['value'] = $data['text'];
            $host = $this->mapper->toHost($row);
            $return[] = $host;
        }
        return $return;
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::productAttributeI18nPull($data['attribute_id']);
    }

    public function pushData($data)
    {
        throw new DataAlreadyFetchedException();
    }
}