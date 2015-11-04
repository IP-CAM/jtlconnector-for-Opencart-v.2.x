<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class ProductAttrI18n extends I18nBaseMapper
{
    protected $pull = [
        'productAttrId' => 'attribute_id',
        'name' => null,
        'value' => null,
        'languageISO' => null
    ];

    protected function name(array $data)
    {
        return isset($data['name']) ? html_entity_decode($data['name']) : '';
    }

    protected function value(array $data)
    {
        return isset($data['value']) ? html_entity_decode($data['value']) : '';
    }
}