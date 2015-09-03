<?php

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class ProductFileDownloadI18n extends I18nBaseMapper
{
    protected $pull = [
        'name' => 'name',
        'languageISO' => null
    ];
}