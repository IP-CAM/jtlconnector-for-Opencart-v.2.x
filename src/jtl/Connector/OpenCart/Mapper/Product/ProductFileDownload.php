<?php

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductFileDownload extends BaseMapper
{
    protected $pull = [
        'fileDownloadId' => 'download_id',
        'productId' => 'product_id'
    ];
}