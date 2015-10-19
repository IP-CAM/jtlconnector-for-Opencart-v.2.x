<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\GlobalData\MeasurementUnitI18n;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapperTest;

class MeasurementUnitI18nTest extends AbstractMapperTest
{

    protected function getMapper()
    {
        return new MeasurementUnitI18n();
    }

    protected function getEndpoint()
    {
        return [
            'id' => '1',
            'title' => 'Millimeter',
            'code' => 'de'
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\MeasurementUnitI18n();
        $result->setMeasurementUnitId(new Identity("1", 0));
        $result->setName('Millimeter');
        $result->setLanguageISO("ger");
        return $result;
    }

    public function testPush()
    {
    }
}
