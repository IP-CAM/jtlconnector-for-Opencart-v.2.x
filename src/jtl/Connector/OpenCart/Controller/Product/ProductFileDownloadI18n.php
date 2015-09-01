<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Core\Model\DataModel;
use jtl\Connector\OpenCart\Controller\BaseController;

class ProductFileDownloadI18n extends BaseController
{
    public function pullData(DataModel $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery(DataModel $data, $limit = null)
    {
        return sprintf('
            SELECT dd.name, l.code
            FROM oc_download_description dd
            LEFT JOIN oc_language l ON dd.language_id = l.language_id'
        );
    }

    public function pushData(DataModel $data)
    {

    }
}
