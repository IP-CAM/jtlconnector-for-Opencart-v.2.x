<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Model\ProductStockLevel as ProductStockLevelModel;
use jtl\Connector\OpenCart\Exceptions\DataAlreadyFetchedException;
use jtl\Connector\OpenCart\Utility\SQLs;

class ProductStockLevel extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::productStockLevelPull($limit);
    }

    public function pushData(ProductStockLevelModel $data, $model)
    {
        $query = SQLs::productStockLevelPush($data->getProductId()->getEndpoint(), $data->getStockLevel());
        $this->database->query($query);
        return $data;
    }
}
