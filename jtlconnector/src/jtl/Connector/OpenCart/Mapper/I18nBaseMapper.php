<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Core\Utilities\Language;

class I18nBaseMapper extends BaseMapper
{
    protected function languageISO($data)
    {
        return is_null($data['code']) ? null : Language::convert($data['code']);
    }
}