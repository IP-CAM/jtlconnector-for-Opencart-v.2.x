<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\OpenCart\Mapper\BaseMapper;

class Product extends BaseMapper
{
    protected $pull = [
//        'basePriceUnitId' => 'Identity',
        'id' => 'product_id',
        'manufacturerId' => 'manufacturer_id',
        'creationDate' => 'date_added',
        'ean' => 'ean',
        'height' => 'height',
        'isActive' => 'status',
        'isbn' => 'isbn',
        'availableFrom' => 'date_available',
//        'basePriceDivisor' => 'double',
//        'basePriceFactor' => 'double',
//        'basePriceQuantity' => 'double',
//        'basePriceUnitCode' => 'string',
//        'basePriceUnitName' => 'string',
//        'considerBasePrice' => 'boolean',
        'length' => 'length',
//        'manufacturerNumber' => 'string',
        'minimumOrderQuantity' => 'minimum',
        'modified' => 'date_modified',
//        'nextAvailableInflowDate' => 'DateTime',
//        'nextAvailableInflowQuantity' => 'double',
        'originCountry' => 'location',
//        'packagingQuantity' => 'double',
        'productWeight' => 'weight',
        'serialNumber' => 'model',
//        'shippingWeight' => 'double',
        'sku' => 'sku',
        'sort' => 'sort_order',
        'stockLevel' => 'Product\ProductStockLevel',
//        'considerStock' => 'boolean',
//        'considerVariationStock' => 'boolean',
        'upc' => 'upc',
        //'vat' => 'double',
        'width' => 'width',
        'attributes' => 'Product\ProductAttr',
        'categories' => 'Product\Product2Category',
//        'checksums' => '\jtl\Connector\Model\ProductChecksum',
//        'configGroups' => '\jtl\Connector\Model\ProductConfigGroup',
//        'customerGroupPackagingQuantities' => '\jtl\Connector\Model\CustomerGroupPackagingQuantity',
        'fileDownloads' => 'Product\ProductFileDownload',
        'i18ns' => 'Product\ProductI18n',
        'prices' => 'Product\ProductPrice',
        'specialPrices' => 'Product\ProductSpecialPrice',
        'variations' => 'Product\ProductVariation'
    ];

    protected $push = [
        'product_id' => 'id',
        'manufacturer_id' => 'manufacturerId',
        'date_added' => 'creationDate',
        'date_modified' => 'modified',
        'ean' => 'ean',
        'sku' => 'sku',
        'status' => 'isActive',
        'isbn' => 'isbn',
        'date_available' => 'availableFrom',
        'minimum' => 'minimumOrderQuantity',
        'location' => 'originCountry',
        'weight' => 'productWeight',
        'height' => 'height',
        'length' => 'length',
        'width' => 'width',
        'model' => 'sku',
        'sort_order' => 'sort',
        'upc' => 'upc',
        'product_store' => null,
        'jan' => null,
        'mpn' => null,
        'subtract' => null,
        'stock_status_id' => null,
        'shipping' => null,
        'price' => null,
        'points' => null,
        'weight_class_id' => null,
        'length_class_id' => null,
        'tax_class_id' => null,
        'keyword' => null,
        'Product\Product2Category' => 'categories',
        'Product\ProductI18n' => 'i18ns',
        'Product\ProductAttr' => 'attributes',
        'Product\ProductPrice' => 'prices',
        'Product\ProductStockLevel' => 'stockLevel',
        'Product\ProductVariation' => 'variations'
    ];

    protected function jan()
    {
        return "";
    }

    protected function mpn()
    {
        return "";
    }

    protected function subtract(ProductModel $data)
    {
        if ($data->getConsiderStock() === false) {
            return null;
        } else {
            return $data->getMinimumOrderQuantity();
        }
    }

    protected function stock_status_id()
    {
        return null;
    }

    protected function shipping()
    {
        return null;
    }

    protected function price()
    {
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

    protected function tax_class_id()
    {
        return null;
    }

    protected function keyword()
    {
        return null;
    }

    protected function product_store()
    {
        return [intval(0)];
    }
}