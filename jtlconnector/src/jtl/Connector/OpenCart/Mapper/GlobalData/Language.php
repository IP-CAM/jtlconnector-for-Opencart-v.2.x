<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\GlobalData;

use jtl\Connector\Core\Utilities\Language as LanguageUtil;
use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class Language extends I18nBaseMapper
{
    protected $pull = [
        'id' => 'language_id',
        'nameGerman' => 'name',
        'isDefault' => 'is_default',
        'languageISO' => null
    ];
}