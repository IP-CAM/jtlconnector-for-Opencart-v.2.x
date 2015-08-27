<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\OpenCart\Controller\BaseController;

class GlobalData extends BaseController
{

    public function pullData($data, $model, $limit = null)
    {
        $model = $this->mapper->toHost();
        return [$model];
    }

    protected function pullQuery($data, $limit = null)
    {
        // TODO: Implement pullQuery() method.
    }

    protected function pushData($data, $model)
    {
        // TODO: Implement pushData() method.
    }
}
