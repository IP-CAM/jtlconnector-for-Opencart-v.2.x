<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;

class Category extends BaseController
{
    private static $idCache = [];

    public function pullData($data, $model, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data, $limit);
        $result = $this->db->query($query);
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

    /*public function pushData($data)
    {
        if (isset(static::$idCache[$data->getParentCategoryId()->getHost()])) {
            $data->getParentCategoryId()->setEndpoint(static::$idCache[$data->getParentCategoryId()->getHost()]);
        }
        $category = $this->mapper->toEndpoint($data);
        $id = $category->save();
        $data->getId()->setEndpoint($id);
        static::$idCache[$data->getId()->getHost()] = $id;
        $this->addMeta($data);
        return $data;
    }

    public function deleteData($data)
    {
        $hostId = $data->getId()->getHost();
        unset(static::$idCache[$hostId]);
        $this->db->query('DELETE FROM jtl_connector_link WHERE hostId = ' . $hostId);
        $this->db->query('DELETE FROM oc_category WHERE category_id = ' . $hostId);
        $this->db->query('DELETE FROM oc_category_description WHERE category_id = ' . $hostId);
        return $data;
    }*/

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
        return $this->db->query(sprintf('
			SELECT COUNT(*)
			FROM oc_category c
			LEFT JOIN jtl_connector_link l ON c.category_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_CATEGORY
        ));
    }
}
