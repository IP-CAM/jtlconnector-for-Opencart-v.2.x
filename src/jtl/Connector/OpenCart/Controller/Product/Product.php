<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Controller\MainEntityController;

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
        // TODO: Implement pushData() method.
    }

    protected function deleteData($data, $model)
    {
        // TODO: Implement deleteData() method.
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
