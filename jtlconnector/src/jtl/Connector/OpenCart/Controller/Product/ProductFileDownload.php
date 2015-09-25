<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
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

    public function pushData(ProductModel $data, &$model)
    {
        foreach ($data->getFileDownloads() as $fileDownload) {
            $download = $this->mapper->toEndpoint($fileDownload);
            foreach ($fileDownload->getI18ns() as $i18n) {

            }
            $model['product_download'][] = $download;
        }
        //var_dump($model['product_download']);
    }
}
