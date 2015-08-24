<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\OpenCart\Mapper\ProductAttrI18n;
use PHPUnit_Framework_TestCase;

class ProductAttrI18nTest extends PHPUnit_Framework_TestCase
{
    private $mapper = null;

    private $endpoint = [
        'productAttrId' => '25',
        'languageISO' => 'de',
        'name' => 'Color',
        'value' => 'red'
    ];
    private $host = [
        'attribute_id' => 25,
        'code' => 'de',
        'name' => 'Color',
        'text' => 'red'
    ];

    protected function setUp()
    {
        $this->mapper = new ProductAttrI18n();
    }

    public function _testToEndpoint()
    {
        $result = $this->mapper->toEndpoint(json_encode($this->host));
        $this->assertEquals($this->endpoint, $result);
    }

    public function testToHost()
    {
        $result = $this->mapper->toHost($this->endpoint);
        var_dump($result);die();
        $this->assertEquals($this->host, $result);
    }
}
