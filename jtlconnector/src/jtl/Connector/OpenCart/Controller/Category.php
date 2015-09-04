<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Utility\OpenCart;

class Category extends MainEntityController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT c.*
            FROM oc_category c
            LEFT JOIN jtl_connector_link l ON c.category_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_CATEGORY, $limit
        );
    }

    public function pushData($data, $model)
    {
        $category = OpenCart::getInstance()->loadModel('catalog/category');
        $endpoint = $this->mapper->toEndpoint($data);
        if (is_null($data->getId()->getEndpoint())) {
            $id = $category->addCategory($endpoint);
            $data->getId()->setEndpoint($id);
        } else {
            $category->editCategory($data->getId()->getEndpoint(), $this->mapper->toEndpoint($data));
        }
        return $data;
    }

    protected function deleteData($data, $model)
    {
        $category = OpenCart::getInstance()->loadModel('catalog/category');
        $category->deleteCategory(intval($data->getId()->getEndpoint()));
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(sprintf('
			SELECT COUNT(*)
			FROM oc_category c
			LEFT JOIN jtl_connector_link l ON c.category_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_CATEGORY
        ));
    }
}
