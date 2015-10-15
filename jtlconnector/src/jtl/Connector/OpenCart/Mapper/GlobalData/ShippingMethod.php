<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper\GlobalData;

use jtl\Connector\OpenCart\Mapper\BaseMapper;
use jtl\Connector\OpenCart\Utility\OpenCart;

class ShippingMethod extends BaseMapper
{
    protected $pull = [
        'id' => 'extension_id',
        'name' => null
    ];

    protected function name(array $data)
    {
        $oc = OpenCart::getInstance();
        return $oc->getModelString('shipping/' . $data['code'], 'text_title');
    }
}