<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

class CategoryI18n extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT c.*, l.code
            FROM oc_category_description c
            LEFT JOIN oc_language l ON c.language_id = l.language_id
            WHERE c.category_id = %d',
            $data['category_id']
        );
    }

    public function pushData($data, &$model)
    {
        parent::pushDataI18n($data, $model, 'category_description');
    }
}