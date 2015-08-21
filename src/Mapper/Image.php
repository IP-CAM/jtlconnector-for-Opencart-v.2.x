<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class Image extends BaseMapper
{
    protected $pull = [
        'id' => 'id',
        'foreignKey' => 'foreign_key',
        'filename' => 'image',
        'relationType' => null,
        'remoteUrl' => null,
        'sort' => 'sort_order'
    ];
}