<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

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
        'vat' => 'double',
        'width' => 'width',
        'attributes' => 'ProductAttr',
        'categories' => 'Product2Category',
//        'checksums' => '\jtl\Connector\Model\ProductChecksum',
//        'configGroups' => '\jtl\Connector\Model\ProductConfigGroup',
//        'customerGroupPackagingQuantities' => '\jtl\Connector\Model\CustomerGroupPackagingQuantity',
        'fileDownloads' => 'ProductFileDownload',
        'i18ns' => 'ProductI18n',
//        'prices' => 'ProductPrice',
//        'specialPrices' => '\jtl\Connector\Model\ProductSpecialPrice',
//        'specifics' => '\jtl\Connector\Model\ProductSpecific',
        'varCombinations' => 'ProductVarCombination',
        'variations' => 'ProductVariation'
    ];
}