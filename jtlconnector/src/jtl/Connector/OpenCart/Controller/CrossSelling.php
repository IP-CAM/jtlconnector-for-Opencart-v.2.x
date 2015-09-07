<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;

class CrossSelling extends MainEntityController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT DISTINCT pr.product_id
            FROM oc_product_related pr
            LEFT JOIN jtl_connector_link l ON %s = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            'CONCAT_WS("_", pr.product_id, pr.related_id)', IdentityLinker::TYPE_CROSSSELLING, $limit
        );
    }

    /**
     * @param $data \jtl\Connector\Model\CrossSelling
     */
    protected function pushData($data, $model)
    {
        $this->deleteData($data);
        $id = $data->getProductId()->getEndpoint();
        if (!empty($id)) {
            foreach ($data->getItems() as $item) {
                foreach ($item->getProductIds() as $relatedId) {
                    $item = new \stdClass();
                    $item->product_id = $id;
                    $item->related_id = $relatedId->getEndpoint();
                    $this->database->insert($item, 'oc_product_related');
                }
            }
        }
        return $data;
    }

    /**
     * @param $data \jtl\Connector\Model\CrossSelling
     * @return mixed
     */
    protected function deleteData($data)
    {
        $id = $data->getProductId()->getEndpoint();
        if (!empty($id)) {
            $this->database->query(sprintf('DELETE FROM oc_product_related WHERE product_id = %d', $id));
        }
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(sprintf('
			SELECT COUNT(DISTINCT(pr.product_id))
			FROM oc_product_related pr
			LEFT JOIN jtl_connector_link l ON %s = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            'CONCAT_WS("_", pr.product_id, pr.related_id)', IdentityLinker::TYPE_CROSSSELLING
        ));
    }
}
