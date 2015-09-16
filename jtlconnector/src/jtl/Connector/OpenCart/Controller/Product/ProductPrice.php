<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Exceptions\DataAlreadyFetchedException;

class ProductPrice extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return [$this->mapper->toHost($data)];
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new DataAlreadyFetchedException();
    }

    public function pushData(ProductModel $data, &$model)
    {
        foreach ($data->getPrices() as $price) {
            foreach ($price->getItems() as $item) {
                $model['price'] = $item->getNetPrice();
            }
        }
    }
}
