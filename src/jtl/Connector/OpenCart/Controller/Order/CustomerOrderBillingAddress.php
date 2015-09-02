<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\Order
 */

namespace jtl\Connector\OpenCart\Controller\Order;

use jtl\Connector\OpenCart\Controller\BaseController;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class CustomerOrderBillingAddress extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return $this->mapper->toHost($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new OperationNotPermitedException("Data already fetched.");
    }
}