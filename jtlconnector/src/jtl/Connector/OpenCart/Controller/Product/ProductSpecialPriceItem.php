<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\ProductSpecialPrice;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Exceptions\DataAlreadyFetchedException;

class ProductSpecialPriceItem extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return [$this->mapper->toHost($data)];
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new DataAlreadyFetchedException();
    }

    public function pushData(ProductSpecialPrice $data, &$model)
    {
        $endpoint = $this->mapper->toEndpoint($data);
        $model = array_merge($model, $endpoint);
    }
}
