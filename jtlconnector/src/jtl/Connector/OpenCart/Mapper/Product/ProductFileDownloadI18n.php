<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class ProductFileDownloadI18n extends I18nBaseMapper
{
    protected $pull = [
        'name' => 'name',
        'languageISO' => null
    ];
}