<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller\Specific;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Exceptions\DataAlreadyFetchedException;
use jtl\Connector\OpenCart\Utility\SQLs;

class SpecificValueI18n extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery(array $data, $limit = null)
    {
        return SQLs::specificValueI18nPull($data['filter_id']);
    }

    public function pushData($data, &$model)
    {
        throw new DataAlreadyFetchedException();
    }
}