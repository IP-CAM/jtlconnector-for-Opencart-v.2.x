<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\OpenCart\Controller\BaseController;
use stdClass;

class ProductFileDownload extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
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
            $id = $download->getFileDownloadId()->getEndpoint();
            if (!empty($id)) {
                $catObj = new stdClass();
                $catObj->product_id = $data->getId()->getEndpoint();
                $catObj->download_id = $id;
                $this->database->insert($catObj, 'oc_product_to_download');
            }
        }
    }
}