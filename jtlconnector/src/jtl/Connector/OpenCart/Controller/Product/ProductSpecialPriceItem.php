<?php

namespace jtl\Connector\OpenCart\Controller\Product;

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
}
