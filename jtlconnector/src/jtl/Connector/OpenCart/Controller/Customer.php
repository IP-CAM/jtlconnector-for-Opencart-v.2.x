<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;
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
        return sprintf(SQLs::CUSTOMER_PULL, IdentityLinker::TYPE_CUSTOMER, $limit);
    }

    protected function getStats()
    {
        return $this->database->queryOne(sprintf(SQLs::CUSTOMER_DELETE, IdentityLinker::TYPE_CUSTOMER));
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