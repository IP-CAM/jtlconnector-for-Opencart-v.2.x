<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;

class ProductFileDownload extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::productFileDownloadPull($data['product_id']);
    }

    public function pushData(ProductModel $data, &$model)
    {
        foreach ($data->getFileDownloads() as $fileDownload) {
            $download = $this->mapper->toEndpoint($fileDownload);
            foreach ($fileDownload->getI18ns() as $i18n) {

            }
            $model['product_download'][] = $download;
        }
    }
}
