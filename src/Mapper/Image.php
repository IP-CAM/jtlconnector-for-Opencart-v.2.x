<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class Image extends BaseMapper
{
    protected $pull = [
        // ?
        //'foreignKey' => 'Identity',
        // ?
        'id' => null,
        // image ?
        'filename' => null,
        // product_image.image => TYPE_PRODUCT
        // manufacturer.image => TYPE_MANUFACTURER
        // option_value.image => TYPE_SPECIFIC_VALUE
        'relationType' => null,
        // komplett ?
        'remoteUrl' => null,
        'sort' => 'sort_order'
    ];
}