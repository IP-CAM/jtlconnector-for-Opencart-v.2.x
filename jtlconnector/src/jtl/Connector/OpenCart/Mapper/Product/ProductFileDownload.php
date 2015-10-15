<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductFileDownload extends BaseMapper
{
    protected $pull = [
        'fileDownloadId' => 'download_id',
        'productId' => 'product_id'
    ];

    protected $push = [
        'download_id' => 'fileDownloadId',
        'product_id' => 'productId'
    ];
}