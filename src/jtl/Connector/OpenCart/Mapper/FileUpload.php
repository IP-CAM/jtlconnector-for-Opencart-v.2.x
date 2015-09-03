<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class FileUpload extends BaseMapper
{
    protected $pull = [
        'id' => 'upload_id',
        'productId' => 'product_id'
    ];
}