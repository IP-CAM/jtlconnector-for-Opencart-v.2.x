<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\OpenCart\Mapper\BaseMapper;
use PHPUnit_Framework_TestCase;

abstract class AbstractMapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var BaseMapper
     */
    private $mapper;
    protected $host;
    protected $endpoint;
    protected $mappedHost;
    protected $mappedEndpoint;

    protected function setUp()
    {
        parent::setUp();
        $this->mapper = $this->getMapper();
        $this->host = $this->getHost();
        $this->endpoint = $this->getEndpoint();
        $this->mappedHost = $this->getMappedHost();
        $this->mappedEndpoint = $this->getMappedEndpoint();
    }

    abstract protected function getMapper();

    /**
     * @return mixed The data which is pushed from the Wawi to the endpoint.
     */
    protected function getHost()
    {
    }

    /**
     * @return array The data which should be there after mapping the host to the endpoint.
     */
    protected function getMappedHost()
    {
    }

    /**
     * @return array The data which is pulled by the controller from database.
     */
    protected function getEndpoint()
    {
    }

    /**
     * @return mixed The data which should be there after mapping the endpoint to the host.
     */
    protected function getMappedEndpoint()
    {
    }

    public function testPush()
    {
        $result = $this->mapper->toEndpoint($this->host);
        $this->assertEquals($this->mappedEndpoint, $result);
    }

    public function testPull()
    {
        $result = $this->mapper->toHost($this->endpoint);
        $this->assertEquals($this->mappedHost, $result);
    }
}