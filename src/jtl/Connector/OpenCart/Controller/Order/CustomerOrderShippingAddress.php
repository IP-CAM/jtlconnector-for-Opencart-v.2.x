<?php

namespace jtl\Connector\OpenCart\Controller\Order;

use jtl\Connector\OpenCart\Controller\BaseController;

class CustomerOrderShippingAddress extends BaseController
{

    public function pullData($data, $model, $limit = null)
    {
        return $this->mapper->toHost($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new OperationNotPermitedException("Data already fetched");
    }
}