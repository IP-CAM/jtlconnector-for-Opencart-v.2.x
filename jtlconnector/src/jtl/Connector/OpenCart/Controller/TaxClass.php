<?php

namespace jtl\Connector\OpenCart\Controller;

class TaxClass extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return 'SELECT * FROM oc_tax_class';
    }
}