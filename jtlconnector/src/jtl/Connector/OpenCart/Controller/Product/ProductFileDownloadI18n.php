<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Exceptions\DataAlreadyFetchedException;

class ProductFileDownloadI18n extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT dd.name, l.code
            FROM oc_download_description dd
            LEFT JOIN oc_language l ON dd.language_id = l.language_id'
        );
    }

    public function pushData($data)
    {
        throw new DataAlreadyFetchedException();
    }
}
