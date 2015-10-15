<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\Specific;

use jtl\Connector\Model\Specific as SpecificModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;

class SpecificI18n extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::specificI18nPull($data['filter_group_id']);
    }

    public function pushData(SpecificModel $data, &$model)
    {
        parent::pushDataI18n($data, $model, 'filter_group_description');
    }
}