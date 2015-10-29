<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\Core\Controller\Controller;
use jtl\Connector\Core\Database\Mysql;
use jtl\Connector\Core\Model\QueryFilter;
use jtl\Connector\Core\Utilities\Singleton;
use jtl\Connector\Database\Sqlite3;
use PHPUnit_Framework_TestCase;

define('HTTPS_CATALOG', '');

abstract class AbstractControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Controller
     */
    protected $controller;

    protected function setUp()
    {
        parent::setUp();
        $this->controller = $this->getController();
    }

    abstract protected function getController();

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testPush()
    {

    }

    public function testPull()
    {
        $result = $this->controller->pull(new QueryFilter());
    }
}

class Db extends Singleton
{
    protected $db;

    public function __construct()
    {
        $mysql = Mysql::getInstance();
        $mysql->connect([
            'host' => $GLOBALS['DB_HOST'],
            'name' => $GLOBALS['DB_NAME'],
            'user' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASSWORD']
        ]);
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
        if ($result instanceof \mysqli_result) {
            $return = mysqli_fetch_row($result)[0];
        }
        return $return;
    }
}