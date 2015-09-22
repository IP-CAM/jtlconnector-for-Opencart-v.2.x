<?php


namespace jtl\Connector\OpenCart\Checksum;

use jtl\Connector\Checksum\IChecksumLoader;
use jtl\Connector\Core\Logger\Logger;
use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Utility\Db;

class ChecksumLoader implements IChecksumLoader
{
    /**
     * @var Db
     */
    protected $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    public function read($endpointId, $type)
    {
        if ($endpointId === null || $type !== IdentityLinker::TYPE_PRODUCT) {
            return '';
        }

        $result = $this->db->queryOne(sprintf('
            SELECT checksum
            FROM jtl_connector_product_checksum
            WHERE product_id = ?',
            $endpointId
        ));

        Logger::write(sprintf('Read with endpointId (%s), type (%s) - result (%s)', $endpointId, $type, $result),
            Logger::DEBUG, 'checksum');

        return is_null($result) ? '' : $result;
    }

    public function write($endpointId, $type, $checksum)
    {
        if ($endpointId === null || $type !== IdentityLinker::TYPE_PRODUCT) {
            return false;
        }

        Logger::write(sprintf('Write with endpointId (%s), type (%s) - checksum (%s)', $endpointId, $type, $checksum),
            Logger::DEBUG, 'checksum');

        $statement = $this->db->query(sprintf('
            INSERT IGNORE INTO jtl_connector_product_checksum (product_id, checksum)
            VALUES (%d, %d)',
            $endpointId, $checksum
        ));

        return $statement ? true : false;
    }

    public function delete($endpointId, $type)
    {
        if ($endpointId === null || $type !== IdentityLinker::TYPE_PRODUCT) {
            return false;
        }

        $rows = $this->db->query(sprintf('
            DELETE FROM jtl_connector_product_checksum
            WHERE product_id = %d',
            $endpointId
        ));

        Logger::write(sprintf('Delete with endpointId (%s), type (%s)', $endpointId, $type), Logger::DEBUG, 'checksum');

        return $rows ? true : false;
    }
}
