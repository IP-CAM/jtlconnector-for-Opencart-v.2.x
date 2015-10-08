<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
use jtl\Connector\OpenCart\Utility\SQLs;

class Customer extends MainEntityController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::customerPull($limit);
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::customerStats());
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