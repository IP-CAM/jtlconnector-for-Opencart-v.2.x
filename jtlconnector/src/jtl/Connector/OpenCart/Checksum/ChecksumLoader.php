<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Checksum;

use jtl\Connector\Checksum\IChecksumLoader;
use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Utility\Db;
use jtl\Connector\OpenCart\Utility\SQLs;

class ChecksumLoader implements IChecksumLoader
{
    private $database;

    public function __construct()
    {
        $this->database = Db::getInstance();
    }

    public function read($endpointId, $type)
    {
        if ($endpointId === null || $type !== IdentityLinker::TYPE_PRODUCT) {
            return '';
        }
        $result = $this->database->queryOne(SQLs::checksumRead($endpointId, $type));
        return is_null($result) ? '' : $result;
    }

    public function write($endpointId, $type, $checksum)
    {
        if ($endpointId === null || $type !== IdentityLinker::TYPE_PRODUCT) {
            return false;
        }
        $statement = $this->database->query(SQLs::checksumWrite($endpointId, $type, $checksum));
        return $statement ? true : false;
    }

    public function delete($endpointId, $type)
    {
        if ($endpointId === null || $type !== IdentityLinker::TYPE_PRODUCT) {
            return false;
        }
        $rows = $this->database->query(SQLs::checksumDelete($endpointId, $type));
        return $rows ? true : false;
    }
}
