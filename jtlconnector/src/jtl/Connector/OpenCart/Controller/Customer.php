<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\OpenCart\Utility\SQLs;

class Customer extends MainEntityController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery(array $data, $limit = null)
    {
        return SQLs::customerPull($limit);
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::customerStats());
    }
}