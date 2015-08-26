<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 25.08.2015
 * Time: 14:10
 */

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\OpenCart\Mapper\CategoryI18n;

class CategoryI18nTest extends AbstractMapper
{
    function getMapper()
    {
        return new CategoryI18n();
    }

    function getHost()
    {
        return [
            'categoryId' => 1,
            'name' => 'English Product Name',
            'description' => 'This is a description.',
            'languageISO' => 'EN',
            'metaDescription' => 'This is a meta description.',
            'metaKeywords' => 'These are meta keywords.'
        ];
    }

    function getEndpoint()
    {
        return [
            'category_id' => "1",
            'name' => 'English Product Name',
            'description' => 'This is a description.',
            'code' => 'en',
            'meta_description' => 'This is a meta description.',
            'meta_keyword' => 'These are meta keywords.'
        ];
    }

    protected function assertToHost($result)
    {
        $this->assertEquals($this->host['categoryId'], $result->getCategoryId()->getEndpoint());
        $this->assertEquals($this->host['name'], $result->getName());
        $this->assertEquals($this->host['description'], $result->getDescription());
        // TODO: Code and language are both not working
        //$this->assertEquals($this->host['languageISO'], $result->getLanguageISO());
        $this->assertEquals($this->host['metaDescription'], $result->getMetaDescription());
        $this->assertEquals($this->host['metaKeywords'], $result->getMetaKeywords());
        // Default values
        $this->assertEmpty($result->getURLPath());
        $this->assertEmpty($result->getTitleTag());
    }
}
