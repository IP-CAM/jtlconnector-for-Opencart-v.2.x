<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;

class Manufacturer extends MainEntityController
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
            SELECT m.*
            FROM oc_manufacturer m
            LEFT JOIN jtl_connector_link l ON m.manufacturer_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_MANUFACTURER, $limit
        );
    }

    public function pushData($data, $model)
    {
        // TODO: Implement pushData() method.
    }

    protected function deleteData($data, $model)
    {
        // TODO: Implement deleteData() method.
    }

    protected function getStats()
    {
        return $this->database->query(sprintf('
			SELECT COUNT(*)
			FROM oc_manufacturer m
			LEFT JOIN jtl_connector_link l ON m.manufacturer_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_MANUFACTURER
        ));
    }
}
