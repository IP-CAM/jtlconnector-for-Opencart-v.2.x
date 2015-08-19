<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 19.08.2015
 * Time: 12:32
 */

namespace jtl\Connector\OpenCart\Mapper;


use jtl\Connector\Core\Utilities\Language;

class CustomerGroupI18n extends BaseMapper
{
    protected $pull = [
        'customerGroupId' => 'customer_group_id',
        'languageISO' => null,
        'name' => 'name'
    ];

    protected function languageISO($data)
    {
        return Language::convert($data['code']);
    }
}