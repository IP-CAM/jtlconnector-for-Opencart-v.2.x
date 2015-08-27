<?php
namespace jtl\Connector\OpenCart\Controller;

use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class ProductSpecialPriceItem extends BaseController
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
