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
        $return = [];
        $query = $this->pullQuery($data, $limit);
        $result = $this->database->query($query);
        foreach ($result as $row) {
            $model = $this->mapper->toHost($row);
            $return[] = $model;
        }
        return $return;
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

    protected function pushData($data, $model)
    {
        // TODO: Implement pushData() method.
    }

    protected function deleteData($data, $model)
    {
        $id = $data->getProductId()->getEndpoint();
        if (!is_null($id)) {
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
