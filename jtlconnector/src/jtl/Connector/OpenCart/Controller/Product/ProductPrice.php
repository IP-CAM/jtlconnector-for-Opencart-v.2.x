<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\OpenCart\Mapper\ProductPriceItem;

class ProductPrice extends \jtl\Connector\OpenCart\Controller\ProductPrice
{
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