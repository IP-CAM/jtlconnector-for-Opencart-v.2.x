<?php

namespace jtl\Connector\OpenCart\Mapper;

class CrossSellingItem extends BaseMapper
{
    protected $pull = [
        'productIds' => ''
    ];
}