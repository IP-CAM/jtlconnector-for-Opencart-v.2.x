<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Model\ProductPrice as ProductPriceModel;
use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
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
        $host = $this->mapper->toHost($result[0]);
        $return[] = $host;
        return $return;
    }

    protected function pullQuery(array $data, $limit = null)
    {
        throw new MethodNotAllowedException();
    }

    public function pushData(ProductPriceModel $data, $model)
    {
        $pp = $this->mapper->toEndpoint($data);

        if (empty($pp['customer_group_id'])) {
            $price = $pp['items'][0]['price'];
            $query = SQLs::productPricePush($pp['product_id'], $price);
            $this->database->query($query);
        } else {
            foreach ($pp['items'] as $item) {
                $id = $this->database->queryOne(SQLs::productPrice($pp['product_id'], $pp['customer_group_id']));

                if (is_null($id)) {
                    $query = SQLs::productPriceAdd($pp['product_id'], $pp['customer_group_id'], $item['quantity'], $item['price']);
                    $this->database->query($query);
                } else {
                    $this->database->query(SQLs::productPriceUpdate($id, $item['quantity'], $item['price']));
                }
            }
        }
        return $data;
    }
}