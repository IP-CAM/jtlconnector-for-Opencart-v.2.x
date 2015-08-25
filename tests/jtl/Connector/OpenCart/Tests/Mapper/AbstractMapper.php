<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use PHPUnit_Framework_TestCase;

abstract class AbstractMapper extends PHPUnit_Framework_TestCase
{
    private $mapper;
    protected $endpoint;
    protected $host;

    protected function setUp()
    {
        parent::setUp();
        $this->mapper = $this->getMapper();
        $this->endpoint = $this->getEndpoint();
        $this->host = $this->getHost();
    }

    abstract protected function getMapper();

    abstract protected function getHost();

    abstract protected function getEndpoint();

    abstract protected function assertToHost($result);

    public function _testToEndpoint()
    {
        $result = $this->mapper->toEndpoint(json_encode($this->host));
        $this->assertEquals($this->endpoint, $result);
    }

    public function testToHost()
    {
        $result = $this->mapper->toHost($this->endpoint);
        $this->assertToHost($result);
    }
}