<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\GlobalData
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;

class CustomerGroup extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::customerGroupPull();
    }
}