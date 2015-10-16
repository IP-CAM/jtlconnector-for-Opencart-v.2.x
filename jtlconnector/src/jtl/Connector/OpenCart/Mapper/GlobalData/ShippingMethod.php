<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
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
        return $oc->getFrontendModelString('shipping/' . $data['code'], 'text_description');
    }
}