<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper\GlobalData;

use jtl\Connector\Core\Utilities\Language as LanguageUtil;
use jtl\Connector\OpenCart\Mapper\BaseMapper;

class Language extends BaseMapper
{
    protected $pull = [
        'id' => 'language_id',
        'nameGerman' => 'name',
        'languageISO' => null,
        'isDefault' => 'is_default'
    ];

    protected function languageISO($data)
    {
        return LanguageUtil::convert($data['code']);
    }
}