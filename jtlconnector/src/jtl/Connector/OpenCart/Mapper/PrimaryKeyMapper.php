<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Core\Logger\Logger;
use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\Mapper\IPrimaryKeyMapper;
use jtl\Connector\OpenCart\Utility\Db;
use jtl\Connector\OpenCart\Utility\SQLs;

class PrimaryKeyMapper implements IPrimaryKeyMapper
{
    protected $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    public function getHostId($endpointId, $type)
    {
        return $this->db->queryOne(SQLs::hostId($endpointId, $type));
    }

    public function getEndpointId($hostId, $type, $relationType = null)
    {
        $clause = '';
        if ($type === IdentityLinker::TYPE_IMAGE) {
            $prefix = substr(strtolower($relationType), 0, 1);
            $clause = " AND endpointId LIKE '{$prefix}_%'";
        }
        return $this->db->queryOne(SQLs::endpointId($hostId, $type, $clause));
    }

    public function save($endpointId, $hostId, $type)
    {
        $id = $this->db->query(SQLs::mappingSave($endpointId, $hostId, $type));
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
        return $this->db->query(SQLs::mappingDelete($where));
    }

    public function clear()
    {
        return $this->db->query(SQLs::mappingClear());
    }

    public function gc()
    {
        return true;
    }
}
