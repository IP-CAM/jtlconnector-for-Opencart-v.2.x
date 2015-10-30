<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

class Image extends BaseMapper
{
    protected $pull = [
        'id' => 'id',
        'foreignKey' => 'foreign_key',
        'filename' => 'image',
        'sort' => 'sort_order'
    ];
}