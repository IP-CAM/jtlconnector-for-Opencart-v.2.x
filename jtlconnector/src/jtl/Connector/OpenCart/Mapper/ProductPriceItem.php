<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Model\ProductPriceItem as ProductPriceItemModel;

class ProductPriceItem extends BaseMapper
{
    protected $pull = [
        'netPrice' => 'price',
        'quantity' => 'quantity'
    ];

    protected $push = [
        'price' => 'netPrice',
        'quantity' => null,
        'priority' => null
    ];

    protected function priority()
    {
        return 0;
    }

    protected function quantity(ProductPriceItemModel $item)
    {
        return $item->getQuantity() === 0 ? 1 : $item->getQuantity();
    }
}