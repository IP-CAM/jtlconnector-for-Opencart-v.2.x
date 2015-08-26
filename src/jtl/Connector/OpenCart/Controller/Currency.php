<?php

namespace jtl\Connector\OpenCart\Controller;

class Currency extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery($data, $limit = null)
    {
        return 'SELECT * FROM oc_currency WHERE status = 1';
    }
}