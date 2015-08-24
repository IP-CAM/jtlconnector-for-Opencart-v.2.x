<?php

namespace jtl\Connector\OpenCart\Mapper;

class ProductAttrI18n extends I18nBaseMapper
{
    protected $pull = [
        'productAttrId' => 'attribute_id',
        'languageISO' => null,
        'name' => 'name',
        'value' => 'value'
    ];
}