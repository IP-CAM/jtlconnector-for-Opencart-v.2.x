<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\Mapper\IPrimaryKeyMapper;
use jtl\Connector\OpenCart\Utility\Db;

class PrimaryKeyMapper implements IPrimaryKeyMapper
{
    protected $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    public function getHostId($endpointId, $type)
    {
        $query = sprintf('
            SELECT hostId
            FROM jtl_connector_link
            WHERE endpointId = %s AND type = %s',
            $endpointId, $type
        );
        return $this->db->queryOne($query);
    }

    public function getEndpointId($hostId, $type, $relationType = null)
    {
        $clause = '';
        if ($type === IdentityLinker::TYPE_IMAGE) {
            $prefix = substr(strtolower($relationType), 0, 1);
            $clause = " AND endpointId LIKE '{$prefix}%'";
        }
        $query = sprintf('
            SELECT endpointId
            FROM jtl_connector_link
            WHERE hostId = %s AND type = %s%s',
            $hostId, $type, $clause
        );
        return $this->db->queryOne($query);
    }

    public function save($endpointId, $hostId, $type)
    {
        $query = sprintf('
            INSERT INTO jtl_connector_link (endpointId, hostId, type)
            VALUES ("%s", %s, %s)',
            (string)$endpointId, $hostId, $type
        );
        $id = $this->db->query($query);
        return $id !== false;
    }

    public function delete($endpointId = null, $hostId = null, $type)
    {
        $where = '';
        if ($endpointId !== null && $hostId !== null) {
            $where = sprintf('WHERE endpointId = %s AND hostId = %s AND type = %s', $endpointId, $hostId, $type);
        } elseif ($endpointId !== null) {
            $where = sprintf('WHERE endpointId = %s AND type = %s', $endpointId, $type);
        } elseif ($hostId !== null) {
            $where = sprintf('WHERE hostId = %s AND type = %s', $hostId, $type);
        }

        return $this->db->query("DELETE FROM jtl_connector_link {$where}");
    }

    public function clear()
    {
        return $this->db->query('DELETE FROM jtl_connector_link');
    }

    public function gc()
    {
        return true;
    }
}
