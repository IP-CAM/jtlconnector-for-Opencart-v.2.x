<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Model\Manufacturer as ManufacturerModel;
use jtl\Connector\OpenCart\Utility\SQLs;

class Manufacturer extends MainEntityController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::manufacturerPull($limit);
    }

    public function pushData(ManufacturerModel $data, $model)
    {
        $ocManufacturer = $this->oc->loadAdminModel('catalog/manufacturer');
        if ($ocManufacturer instanceof \ModelCatalogManufacturer) {
            $endpoint = $this->mapper->toEndpoint($data);
            $id = $data->getId()->getEndpoint();
            if (empty($id)) {
                $id = $ocManufacturer->addManufacturer($endpoint);
                $data->getId()->setEndpoint($id);
            } else {
                $ocManufacturer->editManufacturer($id, $endpoint);
            }
        }
        return $data;
    }

    protected function deleteData(ManufacturerModel $data)
    {
        $ocManufacturer = $this->oc->loadAdminModel('catalog/manufacturer');
        if ($ocManufacturer instanceof \ModelCatalogManufacturer) {
            $ocManufacturer->deleteManufacturer($data->getId()->getEndpoint());
        }
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::manufacturerStats());
    }
}
