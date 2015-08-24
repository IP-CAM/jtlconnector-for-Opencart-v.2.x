<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class Language extends BaseMapper
{
    protected $pull = [
        'id' => 'language_id',
        'isDefault' => null,
        'languageISO' => 'code',
        'nameEnglish' => 'name',
        'nameGerman' => 'name'
    ];

    protected function isDefault($data)
    {
        return $data['sort_order'] === 1 ? true : false;
    }
}