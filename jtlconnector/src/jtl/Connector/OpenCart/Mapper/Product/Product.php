<?php
/**
 * @author    Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\OpenCart\Mapper\BaseMapper;

class Product extends BaseMapper
{
    protected $pull = [
        'id'                     => 'product_id',
        'manufacturerId'         => 'manufacturer_id',
        'creationDate'           => 'date_added',
        'ean'                    => 'ean',
        'height'                 => 'height',
        'isActive'               => 'status',
        'isbn'                   => 'isbn',
        'availableFrom'          => 'date_available',
        'length'                 => 'length',
        'minimumOrderQuantity'   => 'minimum',
        'modified'               => 'date_modified',
        'originCountry'          => 'location',
        'shippingWeight'         => 'weight',
        'sku'                    => 'model',
        'sort'                   => 'sort_order',
        'considerStock'          => 'subtract',
        'upc'                    => 'upc',
        'width'                  => 'width',
        'attributes'             => 'Product\ProductAttr',
        'categories'             => 'Product\Product2Category',
        'i18ns'                  => 'Product\ProductI18n',
        'prices'                 => 'Product\ProductPrice',
        'specialPrices'          => 'Product\ProductSpecialPrice',
        'variations'             => 'Product\ProductVariation',
        'stockLevel'             => 'Product\ProductStockLevel',
        'specifics'              => 'Product\ProductSpecific',
        'considerVariationStock' => null,
    ];

    protected $push = [
        'product_id'                  => 'id',
        'manufacturer_id'             => 'manufacturerId',
        'date_added'                  => 'creationDate',
        'date_modified'               => 'modified',
        'ean'                         => 'ean',
        'sku'                         => 'sku',
        'model'                       => 'sku',
        'status'                      => 'isActive',
        'isbn'                        => 'isbn',
        'date_available'              => 'availableFrom',
        'minimum'                     => 'minimumOrderQuantity',
        'location'                    => 'originCountry',
        'weight'                      => 'shippingWeight',
        'height'                      => 'height',
        'length'                      => 'length',
        'width'                       => 'width',
        'sort_order'                  => 'sort',
        'upc'                         => 'upc',
        'subtract'                    => 'considerStock',
        'Product\Product2Category'    => 'categories',
        'Product\ProductI18n'         => 'i18ns',
        'Product\ProductAttr'         => 'attributes',
        'Product\ProductPrice'        => 'prices',
        'Product\ProductStockLevel'   => 'stockLevel',
        'Product\ProductSpecialPrice' => 'specialPrices',
        'Product\ProductVariation'    => 'variations',
        'Product\ProductSpecific'     => 'specifics',
        'product_store'               => null,
        'jan'                         => null,
        'mpn'                         => null,
        'stock_status_id'             => null,
        'shipping'                    => null,
        'price'                       => null,
        'points'                      => null,
        'weight_class_id'             => null,
        'length_class_id'             => null,
        'keyword'                     => null
    ];

    protected function jan()
    {
        return '';
    }

    protected function mpn()
    {
        return '';
    }

    protected function stock_status_id()
    {
        return null;
    }

    protected function shipping()
    {
        return 1;
    }

    protected function price(ProductModel $data)
    {
        $prices = $data->getPrices();
        if (!empty($prices)) {
            $items = $prices[0]->getItems();
            if (!empty($items)) {
                return $items[0]->getNetPrice();
            }
        }
        return null;
    }

    protected function points()
    {
        return null;
    }

    protected function weight_class_id()
    {
        return null;
    }

    protected function length_class_id()
    {
        return null;
    }

    protected function keyword()
    {
        return null;
    }

    protected function product_store()
    {
        return [0];
    }

    protected function considerVariationStock()
    {
        return true;
    }
}