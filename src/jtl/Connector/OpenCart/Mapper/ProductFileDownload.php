<?php

namespace jtl\Connector\OpenCart\Mapper;

class ProductFileDownload extends BaseMapper
{
    protected $pull = [
        'fileDownloadId' => 'download_id',
        'productId' => 'product_id'
    ];
}