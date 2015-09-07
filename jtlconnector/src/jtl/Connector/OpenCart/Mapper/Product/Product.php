<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

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
        'minimumQuantity' => 'minimum',
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
//        'stockLevel' => 'ProductStockLevel',
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
        'status' => 'isActive',
        'isbn' => 'isbn',
        'date_available' => 'availableFrom',
        'minimum' => 'minimumQuantity',
        'location' => 'originCountry',
        'weight' => 'productWeight',
        'height' => 'height',
        'length' => 'length',
        'width' => 'width',
        'model' => 'sku',
        'sort_order' => 'sort',
        'upc' => 'upc',
        'product_store' => null
    ];

    protected function product_store()
    {
        return [intval(0)];
    }
}