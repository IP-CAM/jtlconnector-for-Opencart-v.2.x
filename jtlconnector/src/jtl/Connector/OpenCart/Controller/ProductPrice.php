<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Model\ProductPrice as ProductPriceModel;
use jtl\Connector\OpenCart\Utility\SQLs;

class ProductPrice extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::productPricePull($limit);
    }

    public function pushData(ProductPriceModel $data, $model)
    {
        $prices = $data->getItems();
        if (!empty($prices)) {
            $query = SQLs::productPricePush($data->getProductId()->getEndpoint(), $prices[0]->getNetPrice());
            $this->database->query($query);
        }
        return $data;
    }
}