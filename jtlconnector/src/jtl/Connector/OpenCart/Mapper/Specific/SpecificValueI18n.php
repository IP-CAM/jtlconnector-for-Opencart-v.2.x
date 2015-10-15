<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Specific;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class SpecificValueI18n extends I18nBaseMapper
{
    protected $pull = [
        'specificValueId' => 'filter_id',
        'value' => 'name',
        'languageISO' => null
    ];

    protected $push = [
        'filter_id' => 'specificValueId',
        'name' => 'value'
    ];
}