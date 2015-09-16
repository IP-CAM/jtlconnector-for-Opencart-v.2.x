<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\OpenCart\Controller\BaseController;

class ProductFileDownload extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT *
            FROM oc_product_to_download
            WHERE product_id = %d',
            $data['product_id']
        );
    }

    public function pushData($data)
    {
        foreach ($data->getFileDownloads() as $download) {
            $model['product_download'][] = $this->mapper->toEndpoint($download);
        }
    }
}
