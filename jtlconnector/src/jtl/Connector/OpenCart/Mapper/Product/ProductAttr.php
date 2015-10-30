<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductAttr extends BaseMapper
{
    protected $pull = [
        'id' => 'attribute_id',
        'productId' => 'product_id',
        'i18ns' => 'Product\ProductAttrI18n',
        'isTranslated' => null
    ];

    protected function isTranslated()
    {
        return true;
    }
}