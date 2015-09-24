<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\GlobalData
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class GlobalData extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        $model = $this->mapper->toHost([]);
        return [$model];
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new OperationNotPermitedException("Just pull the different global data childs.");
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::GLOBAL_DATA_STATS);
    }
}
