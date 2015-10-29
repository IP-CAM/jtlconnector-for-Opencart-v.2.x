<?php

namespace jtl\Connector\OpenCart\Tests\Controller;

use jtl\Connector\Core\Model\QueryFilter;
use jtl\Connector\Drawing\ImageRelationType;
use jtl\Connector\OpenCart\Controller\Image;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractControllerTest;
use jtl\Connector\OpenCart\Tests\Mapper\Db;
use jtl\Connector\OpenCart\Utility\Utils;

class ImageTest extends AbstractControllerTest
{
    protected function getController()
    {
        return new ImageMock();
    }

    public function testAddNextImages()
    {
        $return = [];
        $limit = 100;
        $initLimit = 100;
        $methods = [
            'imageProductCoverPull' => ImageRelationType::TYPE_PRODUCT,
            'imageProductsPull' => ImageRelationType::TYPE_PRODUCT,
            'imageCategoryPull' => ImageRelationType::TYPE_CATEGORY,
            'imageManufacturerPull' => ImageRelationType::TYPE_MANUFACTURER,
            'imageProductVariationValuePull' => ImageRelationType::TYPE_PRODUCT_VARIATION_VALUE
        ];
        $this->invokeMethod($this->controller, 'addNextImages', [&$methods, &$return, &$limit]);
        $this->assertEquals($limit, $initLimit - count($return));
    }

    public function testMapImageToHost()
    {
        $picture = [
            'id' => 2,
            'foreign_key' => 3,
            'image' => 'supergeil.png',
            'sort_order' => 2
        ];
        $type = ImageRelationType::TYPE_PRODUCT;
        $model = $this->invokeMethod($this->controller, 'mapImageToHost', [$picture, $type]);
        $this->assertTrue($model instanceof \jtl\Connector\Model\Image);
        $this->assertEquals($type, $model->getRelationType());
        $this->assertEquals(HTTPS_CATALOG . 'image/supergeil.png', $model->getRemoteURL());
    }
}

class ImageMock extends Image
{
    public function __construct()
    {
        parent::__construct(get_parent_class());
    }

    protected function initHelper()
    {
        $this->database = Db::getInstance();
        $this->utils = Utils::getInstance();
    }
}