<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\GlobalData\Language;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapperTest;

class LanguageTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new Language();
    }

    protected function getEndpoint()
    {
        return [
            'language_id' => '1',
            'sort_order' => 1,
            'code' => 'de',
            'name' => 'Deutsch',
            'is_default' => true
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\Language();
        $result->setId(new Identity("1", 0));
        $result->setIsDefault(true);
        $result->setLanguageISO("ger");
        $result->setNameGerman("Deutsch");
        return $result;
    }

    public function testPush()
    {

    }
}
