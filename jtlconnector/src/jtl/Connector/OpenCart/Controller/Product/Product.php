<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Utility\OpenCart;

class Product extends MainEntityController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT p.*
            FROM oc_product p
            LEFT JOIN jtl_connector_link l ON p.product_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_PRODUCT, $limit
        );
    }

    protected function pushData($data, $model)
    {
        if (empty($data->getId()->getEndpoint())) {
            $id = $this->database->query('INSERT INTO oc_product () VALUES ()');
            $data->getId()->setEndpoint($id);
        }
        $endpoint = $this->mapper->toEndpoint($data);
        $product = OpenCart::getInstance()->loadModel('catalog/product');
        $product->editProduct($data->getId()->getEndpoint(), $endpoint);
        return $data;
    }

    protected function deleteData($data)
    {
        // TODO: Keep in mind that picture files are not deleted automatically.
        $product = OpenCart::getInstance()->loadModel('catalog/product');
        $product->deleteProduct($data->getId()->getEndpoint());
    }

    protected function getStats()
    {
        return $this->database->queryOne(sprintf('
			SELECT COUNT(*)
			FROM oc_product p
			LEFT JOIN jtl_connector_link l ON p.product_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_PRODUCT
        ));
    }
}
