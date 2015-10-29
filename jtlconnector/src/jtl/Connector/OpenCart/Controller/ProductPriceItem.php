<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Model\ProductPrice as ProductPriceModel;
use jtl\Connector\OpenCart\Exceptions\DataAlreadyFetchedException;

class ProductPriceItem extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return [$this->mapper->toHost($data)];
    }

    protected function pullQuery(array $data, $limit = null)
    {
        throw new DataAlreadyFetchedException();
    }

    public function pushData(ProductPriceModel $data)
    {
        throw new DataAlreadyFetchedException();
    }
}
