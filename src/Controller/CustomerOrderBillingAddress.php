<?php

namespace jtl\Connector\OpenCart\Controller;

use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class CustomerOrderBillingAddress extends BaseController
{

    public function pullData($data, $model, $limit = null)
    {
        return $this->mapper->toHost($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new OperationNotPermitedException("Use the data of the CustomerOrder pull.");
    }
}