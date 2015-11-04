<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Model\ProductPrice as ProductPriceModel;
use jtl\Connector\OpenCart\Exceptions\DataAlreadyFetchedException;
use jtl\Connector\OpenCart\Utility\SQLs;

class ProductPriceItem extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery(array $data, $limit = null)
    {
        if ($data['customer_group_id'] == 0) {
            return SQLs::productPriceItems($data['product_id']);
        }
        return SQLs::productGroupPriceItems($data['product_id']);
    }

    public function pushData(ProductPriceModel $data)
    {
        throw new DataAlreadyFetchedException();
    }
}
