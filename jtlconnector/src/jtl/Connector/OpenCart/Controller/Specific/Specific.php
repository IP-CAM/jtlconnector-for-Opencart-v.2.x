<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller\Specific;

use jtl\Connector\Model\Specific as SpecificModel;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Controller\Specific\SpecificValue as SpecificValueCtrl;
use jtl\Connector\OpenCart\Utility\SQLs;

class Specific extends MainEntityController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery(array $data, $limit = null)
    {
        return SQLs::specificPull($limit);
    }

    public function pushData(SpecificModel $data, $model)
    {
        if (!$data->getIsGlobal()) {
            $filterGroup = $this->mapper->toEndpoint($data);
            $ocFilter = $this->oc->loadAdminModel('catalog/filter');
            if ($ocFilter instanceof \ModelCatalogFilter) {
                $id = $data->getId()->getEndpoint();
                if (empty($id)) {
                    $id = $ocFilter->addFilter($filterGroup);
                    $data->getId()->setEndpoint($id);
                } else {
                    $ocFilter->editFilter($id, $filterGroup);
                }
            }
            $specificValueCtrl = new SpecificValueCtrl();
            $specificValueCtrl->pushData($data, $model);
        }
        return $data;
    }

    protected function deleteData(SpecificModel $data)
    {
        $ocFilter = $this->oc->loadAdminModel('catalog/filter');
        if ($ocFilter instanceof \ModelCatalogFilter) {
            $ocFilter->deleteFilter($data->getId()->getEndpoint());
        }
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::specificStats());
    }
}