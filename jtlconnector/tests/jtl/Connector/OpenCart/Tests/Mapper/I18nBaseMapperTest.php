<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\Model\DataModel;
use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;
use jtl\Connector\OpenCart\Utility\Constants;

class I18nBaseMapperTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new I18nBaseMapperMock();
    }

    protected function getEndpoint()
    {
        return [
            'code' => 'de'
        ];
    }

    protected function getMappedHost()
    {
        $result = new I18n();
        $result->setLanguageISO('ger');
        return $result;
    }

    public function testPush()
    {
    }
}

class I18n extends DataModel
{
    protected $languageISO = '';

    public function getLanguageISO()
    {
        return $this->languageISO;
    }

    public function setLanguageISO($languageISO)
    {
        $this->languageISO = $languageISO;
    }
}

class I18nBaseMapperMock extends I18nBaseMapper
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct($subClass = null)
    {
        $this->model = Constants::CONNECTOR_TEST_NAMESPACE . 'Mapper\\I18n';
    }

    protected $pull = [
        'languageISO' => null
    ];
}