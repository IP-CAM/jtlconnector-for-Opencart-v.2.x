<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\GlobalData\MeasurementUnit;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapperTest;

class MeasurementUnitTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new MeasurementUnitMock();
    }

    protected function getEndpoint()
    {
        return [
            'id' => '1',
            'unit' => 'mm'
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\MeasurementUnit();
        $result->setId(new Identity('1', 0));
        $result->setDisplayCode('mm');
        $result->setI18ns([]);
        return $result;
    }

    public function testPush()
    {
    }
}

class MeasurementUnitMock extends MeasurementUnit
{
    public function __construct()
    {
        parent::__construct(get_parent_class());
        unset($this->pull['i18ns']);
    }
}