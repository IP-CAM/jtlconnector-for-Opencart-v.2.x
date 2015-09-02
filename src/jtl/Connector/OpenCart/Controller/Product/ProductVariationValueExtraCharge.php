<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\OpenCart\Controller\BaseController;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;


class ProductVariationValueExtraCharge extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return [$this->mapper->toHost($data)];
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new OperationNotPermitedException('Date already fetched.');
    }

    public function pushData($data)
    {
        // TODO:
    }
}
