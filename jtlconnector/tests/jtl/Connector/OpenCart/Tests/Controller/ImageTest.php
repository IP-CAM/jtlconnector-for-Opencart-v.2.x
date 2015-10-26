<?php

namespace jtl\Connector\OpenCart\Tests\Controller;

use jtl\Connector\Core\Model\QueryFilter;
use jtl\Connector\Drawing\ImageRelationType;
use jtl\Connector\OpenCart\Controller\Image;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractControllerTest;
use jtl\Connector\OpenCart\Utility\Db;
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
}

class ImageMock extends Image
{
    protected function initHelper()
    {
        $this->database = Db::getInstance();
        $this->utils = Utils::getInstance();
    }
}