<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\Category;

class CategoryTest extends AbstractMapperTest
{
    function getMapper()
    {
        return new CategoryMock();
    }

    function getHost()
    {
        $result = new \jtl\Connector\Model\Category();
        $result->setId(new Identity("", 1));
        $result->setParentCategoryId(new Identity());
        $result->setLevel(2);
        $result->setSort(3);
        $result->setIsActive(true);
        $result->setAttributes([]);
        $result->setCustomerGroups([]);
        $result->setInvisibilities([]);
        $result->setI18ns([]);
        return $result;
    }

    function getEndpoint()
    {
        return [
            'category_id' => "1",
            'parent_id'   => null,
            'column'      => 0,
            'status'      => 1,
            'sort_order'  => 3,
            'level'       => 2
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\Category();
        $result->setId(new Identity("1", 0));
        $result->setParentCategoryId(new Identity());
        $result->setLevel(2);
        $result->setSort(3);
        $result->setIsActive(true);
        $result->setAttributes([]);
        $result->setCustomerGroups([]);
        $result->setInvisibilities([]);
        $result->setI18ns([]);
        return $result;
    }

    protected function getMappedEndpoint()
    {
        return [
            'category_id'    => '',
            'parent_id'      => '',
            'column'         => 0,
            'status'         => true,
            'sort_order'     => 3,
            'top'            => true,
            'keyword'        => '',
            'level'          => 2,
            'category_store' => [0]
        ];
    }
}

class CategoryMock extends Category
{
    public function __construct()
    {
        parent::__construct(get_parent_class());
        unset($this->pull['i18ns']);
        unset($this->push['CategoryI18n']);
    }
}