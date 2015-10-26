<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 26.10.2015
 * Time: 13:42
 */

namespace jtl\Connector\OpenCart\Tests\Mapper;


use jtl\Connector\Drawing\ImageRelationType;
use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\Image;

class ImageTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new Image();
    }

    protected function getEndpoint()
    {
        return [
            'id' => 2,
            'foreign_key' => 3,
            'image' => 'supergeil.png',
            'sort_order' => 2
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\Image();
        $result->setId(new Identity('2', 0));
        $result->setForeignKey(new Identity('3', 0));
        $result->setFilename('supergeil.png');
        $result->setSort(2);
        return $result;
    }

    public function testPush()
    {
    }
}
