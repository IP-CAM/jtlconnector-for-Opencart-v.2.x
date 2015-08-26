<?php

namespace jtl\Connector\OpenCart\Controller;

class Language extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery($data, $limit = null)
    {
        return 'SELECT * FROM oc_language WHERE status = 1';
    }
}