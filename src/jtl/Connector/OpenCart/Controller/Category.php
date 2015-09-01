<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Core\Model\DataModel;
use jtl\Connector\Linker\IdentityLinker;

class Category extends MainEntityController
{
    private static $idCache = [];

    public function pullData(DataModel $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model, $limit);
    }

    protected function pullQuery(DataModel $data, $limit = null)
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

    public function pushData(DataModel $data, $model)
    {
        /*$category = OpenCart::getInstance()->loadModel('catalog/category');

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
        return $data;*/
    }

    protected function deleteData(DataModel $data, $model)
    {
        /*$id = intval($data->getId()->getEndpoint());
        $category = OpenCart::getInstance()->loadModel('catalog/category');
        $reflection = new ReflectionObject($category);
        var_dump($reflection->getMethods());
        $category->deleteCategory($id);
        return $data;*/
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
