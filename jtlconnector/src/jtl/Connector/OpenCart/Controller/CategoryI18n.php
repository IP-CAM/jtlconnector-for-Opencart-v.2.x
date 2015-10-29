<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\OpenCart\Utility\SQLs;

class CategoryI18n extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery(array $data, $limit = null)
    {
        return SQLs::categoryI18n($data['category_id']);
    }

    public function pushData($data, &$model)
    {
        parent::pushDataI18n($data, $model, 'category_description');
    }
}