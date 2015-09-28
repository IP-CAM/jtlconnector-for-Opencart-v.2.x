<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\GlobalData
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
use jtl\Connector\OpenCart\Utility\SQLs;

class GlobalData extends MainEntityController
{
    public function pullData($data, $model, $limit = null)
    {
        $model = $this->mapper->toHost([]);
        return [$model];
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new MethodNotAllowedException("Just pull the different global data children.");
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::GLOBAL_DATA_STATS);
    }

    protected function pushData($data, $model)
    {
        throw new MethodNotAllowedException();
    }

    protected function deleteData($data)
    {
        throw new MethodNotAllowedException();
    }
}