<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\GlobalData
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\OpenCart\Controller\BaseController;
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
        $query = 'SELECT
            (SELECT COUNT(*) FROM oc_currency) +
            (SELECT COUNT(*) FROM oc_customer_group) +
            (SELECT COUNT(*) FROM oc_language) +
            (SELECT COUNT(*) FROM oc_tax_rate)';
        return $this->database->queryOne($query);
    }
}
