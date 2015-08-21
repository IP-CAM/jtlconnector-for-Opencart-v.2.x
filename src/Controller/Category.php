<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Mapper\PrimaryKeyMapper;
use jtl\Connector\OpenCart\Utility\OpenCart;

class Category extends MainEntityController
{
    private static $idCache = [];

    public function pullData($data, $model, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data, $limit);
        $result = $this->database->query($query);
        foreach ($result as $row) {
            $model = $this->mapper->toHost($row);
            $return[] = $model;
        }
        return $return;
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

        //die(var_dump($this->mapper->toEndpoint($data)));

        if (isset(static::$idCache[$data->getParentCategoryId()->getHost()])) {
            $data->getParentCategoryId()->setEndpoint(static::$idCache[$data->getParentCategoryId()->getHost()]);
        }
        if (isset(static::$idCache[$data->getId()->getHost()])) {
            // update
            $data->getId()->setEndpoint(static::$idCache[$data->getId()->getHost()]);
            //$category->editCategory($data->getId()->getEndpoint(), $this->mapper->toEndpoint($data));
        } else {
            $pkm = new PrimaryKeyMapper();
            $endpoint = $pkm->getEndpointId($data->getId()->getHost(), IdentityLinker::TYPE_CATEGORY);
            if (!isset($endpoint['endpointId'])) {
                // insert
                $endpoint = $category->addCategory($this->mapper->toEndpoint($data));
            } else {
                // update
                //var_dump($this->mapper->toEndpoint($data));
                //$category->editCategory($data->getId()->getEndpoint(), $this->mapper->toEndpoint($data));
            }
            //var_dump($endpoint);
            $data->getId()->setEndpoint($endpoint['endpointId']);
            static::$idCache[$data->getId()->getHost()] = $endpoint['endpointId'];
        }
        return $data;
    }

    protected function deleteData($data, $model)
    {
        $category = OpenCart::getInstance()->loadModel('catalog/category');
        $category->deleteCategory($data->getId()->getEndpoint());
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
