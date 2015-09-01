<?php

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Core\Model\DataModel;

class TaxClass extends BaseController
{
    public function pullData(DataModel $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery(DataModel $data, $limit = null)
    {
        return 'SELECT * FROM oc_tax_class';
    }
}