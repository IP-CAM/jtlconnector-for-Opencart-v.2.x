<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\GlobalData
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\Core\Model\DataModel;
use jtl\Connector\OpenCart\Controller\BaseController;

class Language extends BaseController
{
    public function pullData(DataModel $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery(DataModel $data, $limit = null)
    {
        return 'SELECT * FROM oc_language WHERE status = 1';
    }
}