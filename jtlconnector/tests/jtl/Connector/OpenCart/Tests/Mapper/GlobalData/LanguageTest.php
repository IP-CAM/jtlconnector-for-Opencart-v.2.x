<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\OpenCart\Mapper\GlobalData\Language;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapper;

class LanguageTest extends AbstractMapper
{
    protected function getMapper()
    {
        return new Language();
    }

    protected function getHost()
    {
        return [
            'id' => 1,
            'isDefault' => true,
            'languageISO' => 'de',
            'nameGerman' => 'Deutsch'
        ];
    }

    protected function getEndpoint()
    {
        return [
            'language_id' => '1',
            'sort_order' => 1,
            'code' => 'de',
            'name' => 'Deutsch'
        ];
    }

    protected function assertToHost($result)
    {
        $this->assertEquals($this->host['id'], $result->getId()->getEndpoint());
        $this->assertEquals($this->host['isDefault'], $result->getIsDefault());
        $this->assertEquals($this->host['languageISO'], $result->getLanguageISO());
        $this->assertEquals($this->host['nameGerman'], $result->getNameGerman());
    }
}
