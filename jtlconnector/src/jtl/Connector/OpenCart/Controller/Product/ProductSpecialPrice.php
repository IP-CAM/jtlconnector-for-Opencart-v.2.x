<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Mapper\Product\ProductSpecialPriceItem;
use jtl\Connector\OpenCart\Utility\SQLs;

class ProductSpecialPrice extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf(SQLs::PRODUCT_SPECIAL_PULL, $data['product_id']);
    }

    public function pushData(ProductModel $data, &$model)
    {
        $specialPriceItemMapper = new ProductSpecialPriceItem();
        foreach ($data->getSpecialPrices() as $specialPrice) {
            for ($count = 0; $count <= count($specialPrice->getItems()); $count++) {
                $special = $this->mapper->toEndpoint($specialPrice);
                foreach ($specialPrice->getItems() as $item) {
                    $specialItem = $specialPriceItemMapper->toEndpoint($item);
                    $model['product_special'][] = array_merge($special, $specialItem);
                }
            }
        }
    }
}
