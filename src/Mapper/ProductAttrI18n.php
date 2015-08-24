<?php

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Core\Utilities\Language;

class ProductAttrI18n extends BaseMapper
{
    protected $pull = [
        'productAttrId' => 'attribute_id',
        'languageISO' => null,
        'name' => 'name',
        'value' => 'value'
    ];

    protected function languageISO($data)
    {
        return Language::convert($data['code']);
    }
}