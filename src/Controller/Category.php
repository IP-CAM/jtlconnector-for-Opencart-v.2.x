<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;

class Category extends DataController
{
    const STATS_QUERY = 'SELECT COUNT(*) FROM oc_category c LEFT JOIN jtl_connector_link l ON c.category_id = l.endpointId AND l.type = 1 WHERE l.hostId IS NULL';
    private static $idCache = [];

    protected function pullQuery($data, $limit)
    {
        return sprintf('SELECT c.*
            FROM oc_category c LEFT JOIN jtl_connector_link l ON c.category_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL LIMIT %d', IdentityLinker::TYPE_CATEGORY, $limit
        );
    }

    public function pushData($data)
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

    private function addMeta($data)
    {
        $id = $data->getId()->getEndpoint();
        if (!empty($id)) {
            foreach ($data->getI18ns() as $i18n) {
                $langId = $this->utils->getLanguageId($i18n->getLanguageISO());
                if ($langId !== false) {
                    //$encoder->addSeoEntry($id, 'oxbaseshop', $langId, null, null, null, null, $i18n->getMetaKeywords(),
                    //   $i18n->getMetaDescription());
                }
            }
        }
    }

    public function deleteData($data)
    {
        $hostId = $data->getId()->getHost();
        unset(static::$idCache[$hostId]);
        $this->db->query('DELETE FROM jtl_connector_link WHERE hostId = ' . $hostId);
        $this->db->query('DELETE FROM oc_category WHERE category_id = ' . $hostId);
        $this->db->query('DELETE FROM oc_category_description WHERE category_id = ' . $hostId);
        return $data;
    }

    public function getStats()
    {
        return $this->db->query(self::STATS_QUERY);
    }


}
