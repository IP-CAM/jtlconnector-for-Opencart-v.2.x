<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Mapper\IPrimaryKeyMapper;
use jtl\Connector\OpenCart\Utility\Db;

class PrimaryKeyMapper implements IPrimaryKeyMapper
{
    protected $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    /**
     * Host ID getter
     *
     * @param string $endpointId
     * @param integer $type
     * @return integer|null
     */
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

    /**
     * Endpoint ID getter
     *
     * @param integer $hostId
     * @param integer $type
     * @param string $relationType
     * @return string|null
     */
    public function getEndpointId($hostId, $type, $relationType = null)
    {
        $query = sprintf('
            SELECT endpointId
            FROM jtl_connector_link
            WHERE hostId = %s AND type = %s AND relationType = %s',
            $hostId, $type, $relationType
        );
        return $this->db->queryOne($query);
    }

    /**
     * Save link to database
     *
     * @param string $endpointId
     * @param integer $hostId
     * @param integer $type
     * @return boolean
     */
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

    /**
     * Delete link from database
     *
     * @param string $endpointId
     * @param integer $hostId
     * @param integer $type
     * @return boolean
     */
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

        return $this->db->query(sprintf('DELETE FROM jtl_connector_link %s'), $where);
    }

    /**
     * Clears the entire link table
     *
     * @return boolean
     */
    public function clear()
    {
        return $this->db->query('DELETE FROM jtl_connector_link');
    }

    /**
     * Garbage Collect the entire link table
     *
     * @return boolean
     */
    public function gc()
    {
        return true;
    }
}