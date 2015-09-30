<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class FileUploadI18n extends I18nBaseMapper
{
    protected $pull = [
        'fileUploadId' => 'product_option_id',
        'name' => 'name',
        'languageISO' => null
    ];
}