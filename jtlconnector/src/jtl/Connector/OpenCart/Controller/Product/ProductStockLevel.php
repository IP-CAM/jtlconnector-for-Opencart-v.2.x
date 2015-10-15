<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Exceptions\DataAlreadyFetchedException;

class ProductStockLevel extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return $this->mapper->toHost($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new DataAlreadyFetchedException();
    }

    public function pushData(ProductModel $data, &$model)
    {
        $stockLevel = $data->getStockLevel();
        if (is_null($stockLevel)) {
            $model['quantity'] = 0;
        } else {
            $model['quantity'] = $stockLevel->getStockLevel();
        }
    }
}
