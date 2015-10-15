<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\GlobalData;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class CustomerGroupI18n extends I18nBaseMapper
{
    protected $pull = [
        'customerGroupId' => 'customer_group_id',
        'languageISO' => null,
        'name' => 'name'
    ];
}