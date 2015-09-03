<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\GlobalData
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\OpenCart\Controller\BaseController;

class CustomerGroupI18n extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT c.*, l.code
            FROM oc_customer_group_description c
            LEFT JOIN oc_language l ON c.language_id = l.language_id
            WHERE c.customer_group_id = %d',
            $data['customer_group_id']
        );
    }


}