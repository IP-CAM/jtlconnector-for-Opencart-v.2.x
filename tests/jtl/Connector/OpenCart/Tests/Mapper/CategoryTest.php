<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 25.08.2015
 * Time: 14:10
 */

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\OpenCart\Mapper\Category;

class CategoryTest extends AbstractMapper
{
    function getMapper()
    {
        return new Category();
    }

    function getHost()
    {
        return [
            'id' => 1,
            'parentCategoryId' => 0,
            'level' => 0,
            'isActive' => 1,
            'sort' => 3,
            'i18ns' => []
        ];
    }

    function getEndpoint()
    {
        return [
            'category_id' => "1",
            'parent_id' => "0",
            'column' => 0,
            'status' => 1,
            'sort_order' => 3,
            'CategoryI18n' => []
        ];
    }

    protected function assertToHost($result)
    {
        $this->assertEquals($this->host['id'], $result->getId()->getEndpoint());
        $this->assertEquals($this->host['parentCategoryId'], $result->getParentCategoryId()->getEndpoint());
        $this->assertEquals($this->host['level'], $result->getLevel());
        $this->assertEquals($this->host['isActive'], $result->getIsActive());
        $this->assertEquals($this->host['sort'], $result->getSort());
        $this->assertTrue(empty($result->getI18ns()));
        // Default values
        $this->assertTrue(empty($result->getAttributes()));
        $this->assertTrue(empty($result->getCustomerGroups()));
        $this->assertTrue(empty($result->getInvisibilities()));
    }
}
