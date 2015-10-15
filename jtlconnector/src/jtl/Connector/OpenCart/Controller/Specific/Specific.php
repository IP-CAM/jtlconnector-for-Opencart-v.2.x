<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\Specific;

use jtl\Connector\Model\Specific as SpecificModel;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Controller\Specific\SpecificValue as SpecificValueCtrl;
use jtl\Connector\OpenCart\Utility\SQLs;

class Specific extends MainEntityController
{
    private $ocFilter;

    public function __construct()
    {
        parent::__construct();
        $this->ocFilter = $this->oc->loadAdminModel('catalog/filter');
    }

    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::specificPull($limit);
    }

    public function pushData(SpecificModel $data, $model)
    {
        if (!$data->getIsGlobal()) {
            $filterGroup = $this->mapper->toEndpoint($data);
            if (is_null($data->getId()->getEndpoint())) {
                $id = $this->ocFilter->addFilter($filterGroup);
                $data->getId()->setEndpoint($id);
            } else {
                $this->ocFilter->editFilter($data->getId()->getEndpoint(), $filterGroup);
            }
            $specificValueCtrl = new SpecificValueCtrl();
            $specificValueCtrl->pushData($data, $model);
        }
        return $data;
    }

    protected function deleteData(SpecificModel $data)
    {
        $this->ocFilter->deleteFilter($data->getId()->getEndpoint());
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::specificStats());
    }
}