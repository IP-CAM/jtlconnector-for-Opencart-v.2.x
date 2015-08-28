<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 19.08.2015
 * Time: 12:35
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\OpenCart\Controller\BaseController;


class CustomerGroup extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery($data, $limit = null)
    {
        return 'SELECT * FROM oc_customer_group';
    }
}