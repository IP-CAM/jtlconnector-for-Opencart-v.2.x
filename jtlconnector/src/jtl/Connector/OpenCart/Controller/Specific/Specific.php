<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\Specific;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Utility\SQLs;

class Specific extends MainEntityController
{
    private $ocFilter;

    public function __construct()
    {
        parent::__construct();
        $this->ocFilter = $this->oc->loadAdminModel('catalog/filter');
    }

    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf(SQLs::SPECIFIC_PULL, IdentityLinker::TYPE_SPECIFIC, $limit);
    }

    public function pushData($data, $model)
    {
        $filterGroup = $this->mapper->toEndpoint($data);
        if (is_null($data->getId()->getEndpoint())) {
            $id = $this->ocFilter->addFilter($filterGroup);
            $data->getId()->setEndpoint($id);
        } else {
            $this->ocFilter->editFilter($data->getId()->getEndpoint(), $filterGroup);
        }
        return $data;
    }

    protected function deleteData($data)
    {
        $this->ocFilter->deleteFilter($data->getId()->getEndpoint());
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(sprintf(SQLs::SPECIFIC_STATS, IdentityLinker::TYPE_SPECIFIC));
    }
}