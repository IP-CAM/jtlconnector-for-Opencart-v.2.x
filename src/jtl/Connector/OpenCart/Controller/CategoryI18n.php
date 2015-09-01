<?php
namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Core\Model\DataModel;

class CategoryI18n extends BaseController
{
    public function pullData(DataModel $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery(DataModel $data, $limit = null)
    {
        return sprintf('
            SELECT c.*, l.code
            FROM oc_category_description c
            LEFT JOIN oc_language l ON c.language_id = l.language_id
            WHERE c.category_id = %d',
            $data['category_id']
        );
    }

    public function pushData(DataModel $data, $model)
    {
        return $this->mapper->toEndpoint($data);
    }
}