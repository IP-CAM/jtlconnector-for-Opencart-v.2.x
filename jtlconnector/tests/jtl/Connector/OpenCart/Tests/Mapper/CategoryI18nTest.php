<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\CategoryI18n;

class CategoryI18nTest extends AbstractMapperTest
{
    function getMapper()
    {
        return new CategoryI18n();
    }

    function getHost()
    {
        $result = new \jtl\Connector\Model\CategoryI18n();
        $result->setCategoryId(new Identity("", 1));
        $result->setName("Schokolade");
        $result->setDescription("De Schoggi is echt läcka.");
        $result->setLanguageISO("ger");
        $result->setTitleTag("Schweizer Schokolade");
        $result->setUrlPath("/schoggi/");
        $result->setMetaKeywords("schoggi, schweiz, schwiiz");
        $result->setMetaDescription("Echte Schweizer Schokolade, die so lecker ist.");
        return $result;
    }

    function getEndpoint()
    {
        return [
            'category_id' => "1",
            'name' => 'Schokolade',
            'description' => 'De Schoggi is echt läcka.',
            'code' => 'de',
            'meta_title' => 'Schweizer Schokolade',
            'meta_description' => 'Echte Schweizer Schokolade, die so lecker ist.',
            'meta_keyword' => 'schoggi, schweiz, schwiiz'
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\CategoryI18n();
        $result->setCategoryId(new Identity("1", 0));
        $result->setName("Schokolade");
        $result->setDescription("De Schoggi is echt läcka.");
        $result->setLanguageISO("ger");
        $result->setTitleTag("Schweizer Schokolade");
        $result->setMetaKeywords("schoggi, schweiz, schwiiz");
        $result->setMetaDescription("Echte Schweizer Schokolade, die so lecker ist.");
        return $result;
    }

    protected function getMappedEndpoint()
    {
        return [
            'category_id' => '',
            'name' => 'Schokolade',
            'description' => 'De Schoggi is echt läcka.',
            'meta_title' => 'Schweizer Schokolade',
            'meta_description' => 'Echte Schweizer Schokolade, die so lecker ist.',
            'meta_keyword' => 'schoggi, schweiz, schwiiz'
        ];
    }
}
