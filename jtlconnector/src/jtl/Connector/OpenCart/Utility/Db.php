<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Core\Database\Mysql;
use jtl\Connector\Core\Logger\Logger;
use jtl\Connector\Core\Utilities\Singleton;

class Db extends Singleton
{
    protected $db;

    public function __construct()
    {
        $mysql = Mysql::getInstance();
        $mysql->connect([
            'host' => DB_HOSTNAME,
            'name' => DB_DATABASE,
            'user' => DB_USERNAME,
            'password' => DB_PASSWORD
        ]);
        $mysql->DB()->set_charset("utf8");
        $this->db = $mysql;
    }

    public function query($query)
    {
        return $this->db->query($query);
    }

    public function queryOne($query)
    {
        $return = null;
        $result = mysqli_query($this->db->DB(), $query);
        Logger::write($query, Logger::DEBUG, 'database');
        if ($result instanceof \mysqli_result) {
            $return = mysqli_fetch_row($result)[0];
        }
        return $return;
    }

    /**
     * @return Db
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }
}