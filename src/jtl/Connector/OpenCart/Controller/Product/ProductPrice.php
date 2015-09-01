<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Core\Model\DataModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class ProductPrice extends BaseController
{
    public function pullData(DataModel $data, $model, $limit = null)
    {
        return [$this->mapper->toHost($data)];
    }

    protected function pullQuery(DataModel $data, $limit = null)
    {
        throw new OperationNotPermitedException("Data already fetched.");
    }

    public function pushData(DataModel $data)
    {
        // TODO:
    }
}
