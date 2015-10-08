<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Checksum
 */

namespace jtl\Connector\OpenCart\Checksum;

use jtl\Connector\Checksum\IChecksumLoader;
use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Utility\Db;

class ChecksumLoader implements IChecksumLoader
{
    /**
     * @var Db
     */
    protected $database;

    public function __construct()
    {
        $this->database = Db::getInstance();
    }

    public function read($endpointId, $type)
    {
        if ($endpointId === null || $type !== IdentityLinker::TYPE_PRODUCT) {
            return '';
        }
        $result = $this->database->queryOne(sprintf('
            SELECT checksum
            FROM jtl_connector_checksum
            WHERE endpointId = %d AND type = %d',
            $endpointId, $type
        ));
        return is_null($result) ? '' : $result;
    }

    public function write($endpointId, $type, $checksum)
    {
        if ($endpointId === null || $type !== IdentityLinker::TYPE_PRODUCT) {
            return false;
        }

        $statement = $this->database->query(sprintf('
            INSERT IGNORE INTO jtl_connector_checksum (endpointId, type, checksum)
            VALUES (%d, %d, %d)',
            $endpointId, $type, $checksum
        ));

        return $statement ? true : false;
    }

    public function delete($endpointId, $type)
    {
        if ($endpointId === null || $type !== IdentityLinker::TYPE_PRODUCT) {
            return false;
        }

        $rows = $this->database->query(sprintf('
            DELETE FROM jtl_connector_checksum
            WHERE endpointId = %d AND type = %d',
            $endpointId, $type
        ));

        return $rows ? true : false;
    }
}
