<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Utility\OpenCart;

class Manufacturer extends MainEntityController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model, $limit);
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
        $manufacturer = OpenCart::getInstance()->loadModel('catalog/manufacturer');
        $endpoint = $this->mapper->toEndpoint($data);
        if (is_null($data->getId()->getEndpoint())) {
            $id = $manufacturer->addManufacturer($endpoint);
            $data->getId()->setEndpoint($id);
        } else {
            $manufacturer->editManufacturer($data->getId()->getEndpoint(), $endpoint);
        }
        return $data;
    }

    protected function deleteData($data)
    {
        $manufacturer = OpenCart::getInstance()->loadModel('catalog/manufacturer');
        $manufacturer->deleteManufacturer(intval($data->getId()->getEndpoint()));
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(sprintf('
			SELECT COUNT(*)
			FROM oc_manufacturer m
			LEFT JOIN jtl_connector_link l ON m.manufacturer_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_MANUFACTURER
        ));
    }
}
