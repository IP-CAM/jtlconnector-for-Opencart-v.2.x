<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Utility\SQLs;

class Manufacturer extends MainEntityController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf(SQLs::MANUFACTURER_PULL, IdentityLinker::TYPE_MANUFACTURER, $limit);
    }

    public function pushData($data, $model)
    {
        $manufacturer = $this->oc->loadAdminModel('catalog/manufacturer');
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
        $manufacturer = $this->oc->loadAdminModel('catalog/manufacturer');
        $manufacturer->deleteManufacturer(intval($data->getId()->getEndpoint()));
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::MANUFACTURER_STATS, IdentityLinker::TYPE_MANUFACTURER);
    }
}
