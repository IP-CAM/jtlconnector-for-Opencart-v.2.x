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
//        'measurementUnitId' => 'Identity',
//        'partsListId' => 'Identity',
//        'productTypeId' => 'Identity',
//        'shippingClassId' => 'Identity',
//        'unitId' => 'Identity',
        'availableFrom' => 'date_avalable',
//        'basePriceDivisor' => 'double',
//        'basePriceFactor' => 'double',
//        'basePriceQuantity' => 'double',
//        'basePriceUnitCode' => 'string',
//        'basePriceUnitName' => 'string',
//        'considerBasePrice' => 'boolean',
//        'considerStock' => 'boolean',
//        'considerVariationStock' => 'boolean',
        // viewed count?
//        'isNewProduct' => 'boolean',
        'length' => 'length',
//        'manufacturerNumber' => 'string',
//        'measurementQuantity' => 'double',
//        'measurementUnitCode' => 'string',
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
//        'supplierDeliveryTime' => 'integer',
//        'supplierStockLevel' => 'double',
        'upc' => 'upc',
        'vat' => 'double',
        'width' => 'width',
        'attributes' => 'ProductAttr',
        'categories' => 'Product2Category',
//        'checksums' => '\jtl\Connector\Model\ProductChecksum',
//        'configGroups' => '\jtl\Connector\Model\ProductConfigGroup',
//        'customerGroupPackagingQuantities' => '\jtl\Connector\Model\CustomerGroupPackagingQuantity',
        'fileDownloads' => 'ProductFileDownload',
        'i18ns' => 'ProductI18n'
//        'partsLists' => '\jtl\Connector\Model\ProductPartsList',
//        'prices' => '\jtl\Connector\Model\ProductPrice',
//        'specialPrices' => '\jtl\Connector\Model\ProductSpecialPrice',
//        'specifics' => '\jtl\Connector\Model\ProductSpecific',
//        'varCombinations' => '\jtl\Connector\Model\ProductVarCombination',
//        'variations' => '\jtl\Connector\Model\ProductVariation',
//        'warehouseInfo' => '\jtl\Connector\Model\ProductWarehouseInfo'
    ];
}