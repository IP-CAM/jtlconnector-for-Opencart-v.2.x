<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Core\Utilities\Language;

class I18nBaseMapper extends BaseMapper
{
    protected function languageISO($data)
    {
        return Language::convert($data['code']);
    }
}