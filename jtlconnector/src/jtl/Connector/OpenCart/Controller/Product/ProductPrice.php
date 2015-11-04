<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
use jtl\Connector\OpenCart\Mapper\ProductPriceItem;
use jtl\Connector\OpenCart\Utility\SQLs;

class ProductPrice extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        $return = [];
        $groupPriceQuery = SQLs::productGroupPricesByProduct($data['product_id']);
        $result = $this->database->query($groupPriceQuery);
        foreach ((array)$result as $row) {
            $host = $this->mapper->toHost($row);
            $return[] = $host;
        }
        $productPriceQuery = SQLs::productPriceByProduct($data['product_id']);
        $result = $this->database->query($productPriceQuery);
        if (!empty($result)) {
            $host = $this->mapper->toHost($result[0]);
            $return[] = $host;
        }
        return $return;
    }

    protected function pullQuery(array $data, $limit = null)
    {
        throw new MethodNotAllowedException();
    }

    public function pushData(ProductModel $data, &$model)
    {
        $priceItemMapper = new ProductPriceItem();
        foreach ($data->getPrices() as $price) {
            $groupId = $price->getCustomerGroupId()->getEndpoint();
            if (empty($groupId)) {
                $model['price'] = $price->getItems()[0]->getNetPrice();
            } else {
                $productPrice = $this->mapper->toEndpoint($price);
                foreach ($price->getItems() as $item) {
                    $priceItem = $priceItemMapper->toEndpoint($item);
                    $model['product_discount'][] = array_merge($productPrice, $priceItem);
                }
            }
        }
    }
}