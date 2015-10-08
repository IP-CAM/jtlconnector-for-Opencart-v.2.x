<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\OpenCart\Utility\SQLs;

class CrossSelling extends MainEntityController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::crossSellingPull('', $limit);
    }

    protected function pushData($data, $model)
    {
        $this->deleteData($data);
        $id = $data->getProductId()->getEndpoint();
        if (!empty($id)) {
            foreach ($data->getItems() as $item) {
                foreach ($item->getProductIds() as $relatedId) {
                    $this->database->query(SQLs::crossSellingPush($id, $relatedId->getEndpoint()));
                }
            }
        }
        return $data;
    }

    protected function deleteData($data)
    {
        $id = $data->getProductId()->getEndpoint();
        if (!empty($id)) {
            $this->database->query(SQLs::crossSellingDelete($id));
        }
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::crossSellingStats());
    }
}
