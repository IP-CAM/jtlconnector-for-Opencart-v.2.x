<?php

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Linker\IdentityLinker;

final class SQLs
{
    // <editor-fold defaultstate="collapsed" desc="Product Variation">
    public static function productVariationPull($productId)
    {
        return sprintf('
            SELECT *
            FROM ' . DB_PREFIX . 'product_option po
            LEFT JOIN ' . DB_PREFIX . 'option o ON po.option_id = o.option_id
            WHERE po.product_id = %d AND o.type NOT IN ("checkbox", "file")',
            $productId
        );
    }

    public static function productVariationI18nPull($productOptionId)
    {
        return sprintf('
            SELECT po.product_option_id, od.name, l.code
            FROM ' . DB_PREFIX . 'option_description od
            LEFT JOIN ' . DB_PREFIX . 'product_option po ON po.option_id = od.option_id
            LEFT JOIN ' . DB_PREFIX . 'language l ON l.language_id = od.language_id
            WHERE po.product_option_id = %d',
            $productOptionId
        );
    }

    public static function productVariationValuePull($productOptionId)
    {
        return sprintf('
            SELECT *
            FROM ' . DB_PREFIX . 'product_option_value
            WHERE product_option_id = %d',
            $productOptionId
        );
    }

    public static function productVariationValueI18nPull($productOptionValueId)
    {
        return sprintf('
            SELECT pov.product_option_value_id, ovd.name, l.code
            FROM ' . DB_PREFIX . 'product_option_value pov
            LEFT JOIN ' . DB_PREFIX . 'option_value_description ovd ON pov.option_value_id = ovd.option_value_id
            LEFT JOIN ' . DB_PREFIX . 'language l ON ovd.language_id = l.language_id
            WHERE pov.product_option_value_id = %d',
            $productOptionValueId
        );
    }

    public static function fileUploadPull($limit)
    {
        return sprintf('
            SELECT *
            FROM ' . DB_PREFIX . 'product_option po
            LEFT JOIN ' . DB_PREFIX . 'option o ON po.option_id = o.option_id
            LEFT JOIN ' . DB_PREFIX . 'option_description od ON od.option_id = o.option_id
            WHERE o.type = "file"
            LIMIT %d',
            $limit
        );
    }

    public static function fileUploadPush($productId, $optionId, $required)
    {
        return sprintf('
            INSERT INTO ' . DB_PREFIX . 'product_option (product_id, option_id, required)
            VALUES (%d, %d, %d)',
            $productId, $optionId, $required
        );
    }

    public static function fileUploadDelete($productOptionId)
    {
        return sprintf(
            'DELETE FROM ' . DB_PREFIX . 'product_option WHERE product_option_id = %d',
            $productOptionId
        );
    }
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Option">
    public static function optionId($languageId, $name, $type)
    {
        return sprintf('
            SELECT o.option_id
            FROM ' . DB_PREFIX . 'option o
            LEFT JOIN ' . DB_PREFIX . 'option_description od ON o.option_id = od.option_id
            WHERE od.language_id = %d AND od.name = "%s" AND o.type = "%s"',
            $languageId, $name, $type
        );
    }

    public static function optionValueId($languageId, $name, $optionId)
    {
        return sprintf('
            SELECT ov.option_value_id
            FROM ' . DB_PREFIX . 'option_value ov LEFT JOIN ' . DB_PREFIX . 'option_value_description ovd ON ovd.option_value_id = ov.option_value_id
            WHERE ovd.language_id = %d AND ovd.name = "%s" AND ov.option_id = %d',
            $languageId, $name, $optionId
        );
    }

    public static function obsoleteOptions()
    {
        return '
            SELECT o.option_id
            FROM ' . DB_PREFIX . 'option o
            LEFT JOIN ' . DB_PREFIX . 'product_option po ON po.option_id = o.option_id
            WHERE po.option_id IS NULL';
    }
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Module">
    public static function moduleIdTopProducts()
    {
        return 'SELECT module_id FROM ' . DB_PREFIX . 'module WHERE code = "featured" AND name = "Featured - Wawi"';
    }
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Global Data">
    public static function customerGroupPull()
    {
        return '
            SELECT cg.*, s.key IS NOT NULL as is_default
            FROM ' . DB_PREFIX . 'customer_group cg
            LEFT JOIN ' . DB_PREFIX . 'setting s ON cg.customer_group_id = s.value
            WHERE s.key = "config_customer_group_id"';
    }

    public static function customerGroupI18nPull($customerGroupId)
    {
        return sprintf('
            SELECT c.*, l.code
            FROM ' . DB_PREFIX . 'customer_group_description c
            LEFT JOIN ' . DB_PREFIX . 'language l ON c.language_id = l.language_id
            WHERE c.customer_group_id = %d',
            $customerGroupId
        );
    }

    public static function currencyPull()
    {
        return '
            SELECT c.*, s.key IS NOT NULL as is_default
            FROM ' . DB_PREFIX . 'currency c
            LEFT JOIN ' . DB_PREFIX . 'setting s ON c.code = s.value
            WHERE c.status = 1';
    }

    public static function updateCurrency($currencyId, $value)
    {
        return sprintf(
            'UPDATE ' . DB_PREFIX . 'currency SET value = %d WHERE currency_id = %d',
            $currencyId, $value
        );
    }

    public static function languagePull()
    {
        return '
            SELECT l.*, s.key IS NOT NULL as is_default
            FROM ' . DB_PREFIX . 'language l
            LEFT JOIN ' . DB_PREFIX . 'setting s ON l.code = s.value
            WHERE l.status = 1';
    }

    public static function globalDataStats()
    {
        return '
            (SELECT COUNT(*) FROM ' . DB_PREFIX . 'currency) +
            (SELECT COUNT(*) FROM ' . DB_PREFIX . 'customer_group) +
            (SELECT COUNT(*) FROM ' . DB_PREFIX . 'language) +
            (SELECT COUNT(*) FROM ' . DB_PREFIX . 'tax_rate)';
    }
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Customer Order">
    public static function customerOrderPull($limit)
    {
        return sprintf('
            SELECT o.*, l.code, c.iso_code_3
            FROM ' . DB_PREFIX . 'order o
            LEFT JOIN ' . DB_PREFIX . 'language l ON o.language_id = l.language_id
            LEFT JOIN ' . DB_PREFIX . 'country c ON o.payment_country_id = c.country_id
            LEFT JOIN jtl_connector_link cl ON o.order_id = cl.endpointId AND cl.type = %d
            WHERE cl.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_CUSTOMER_ORDER, $limit
        );
    }

    public static function customerOrderStats()
    {
        return sprintf('
            SELECT COUNT(*)
            FROM ' . DB_PREFIX . 'order o
            LEFT JOIN jtl_connector_link l ON o.order_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_CUSTOMER_ORDER
        );
    }

    public static function customerOrderShippingStatus($orderId)
    {
        return sprintf('
            SELECT os.name, oh.date_added
            FROM ' . DB_PREFIX . 'order o
            LEFT JOIN ' . DB_PREFIX . 'language l ON o.language_id = l.language_id
            LEFT JOIN ' . DB_PREFIX . 'order_status os ON os.order_status_id = o.order_status_id AND os.language_id = l.language_id
            LEFT JOIN ' . DB_PREFIX . 'order_history oh ON oh.order_status_id = o.order_status_id
            WHERE oh.order_id = %d
            ORDER BY oh.date_added
            LIMIT 1',
            $orderId
        );
    }

    public static function customerOrderPaymentStatus($orderId, $comments)
    {
        return sprintf('
            SELECT oh.comment, oh.date_added
            FROM ' . DB_PREFIX . 'order o
            LEFT JOIN ' . DB_PREFIX . 'language l ON o.language_id = l.language_id
            LEFT JOIN ' . DB_PREFIX . 'order_history oh ON oh.order_status_id = o.order_status_id
            WHERE oh.order_id = %d AND oh.comment != "" AND oh.comment IN (%s)
            ORDER BY oh.date_added
            LIMIT 1',
            $orderId, $comments
        );
    }

    public static function customerOrderShippingStatusId($orderId)
    {
        return sprintf('
            SELECT oh.order_status_id
            FROM ' . DB_PREFIX . 'order_status os
            LEFT JOIN ' . DB_PREFIX . 'order_history oh ON oh.order_status_id = os.order_status_id
            WHERE oh.order_id = %d
            ORDER BY oh.date_added
            LIMIT 1',
            $orderId
        );
    }
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Customer Order Item">
    public static function customerOrderProducts($orderId)
    {
        return sprintf('
            SELECT op.*, p.sku
            FROM ' . DB_PREFIX . 'order_product op
            LEFT JOIN ' . DB_PREFIX . 'product p ON p.product_id = op.product_id
            WHERE op.order_id = %d',
            $orderId
        );
    }

    public static function customerOrderShippings($orderId)
    {
        return sprintf('
            SELECT *
            FROM ' . DB_PREFIX . 'order_total
            WHERE code = "shipping" AND order_id = %d',
            $orderId
        );
    }

    public static function customerOrderDiscounts($orderId)
    {
        return sprintf('
            SELECT *
            FROM ' . DB_PREFIX . 'order_total
            WHERE code IN ("coupon", "voucher") AND order_id = %d',
            $orderId
        );
    }

    public static function customerOrderItemVariation($orderId)
    {
        return sprintf('
            SELECT oo.*, u.name AS filename, pov.price_prefix, pov.price
            FROM ' . DB_PREFIX . 'order_option oo
            LEFT JOIN ' . DB_PREFIX . 'product_option po ON oo.product_option_id = po.product_option_id
            LEFT JOIN ' . DB_PREFIX . 'option o ON o.option_id = po.option_id
            LEFT JOIN ' . DB_PREFIX . 'upload u ON u.code = oo.value
            LEFT JOIN ' . DB_PREFIX . 'product_option_value pov ON pov.product_option_value_id = oo.product_option_value_id
            WHERE oo.order_id = %d
            ORDER BY o.sort_order',
            $orderId
        );
    }
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Status Change">
    public static function statusChangeByOrder($orderId)
    {
        return sprintf('SELECT count(*) FROM ' . DB_PREFIX . 'order WHERE order_id = %d', $orderId);
    }

    public static function statusChangeAdd($orderId, $statusId, $payment)
    {
        return sprintf('
            INSERT INTO ' . DB_PREFIX . 'order_history (order_id, order_status_id, notify, comment, date_added)
            VALUES (%d, %d, 0, "Payment: %s", NOW())',
            $orderId, $statusId, $payment
        );
    }

    public static function customerOrderStatusUpdate($orderStatusId, $orderId)
    {
        return sprintf('
            UPDATE ' . DB_PREFIX . 'order
            SET order_status_id = %d, date_modified = NOW()
            WHERE order_id = %d',
            $orderStatusId, $orderId
        );
    }
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Product">
    public static function productInsert()
    {
        return 'INSERT INTO ' . DB_PREFIX . 'product () VALUES ()';
    }

    public static function productPull($limit)
    {
        return sprintf('
            SELECT p.*, tr.rate
            FROM ' . DB_PREFIX . 'product p
            LEFT JOIN ' . DB_PREFIX . 'tax_class tc ON p.tax_class_id = tc.tax_class_id
            LEFT JOIN ' . DB_PREFIX . 'tax_rule r ON r.tax_class_id = tc.tax_class_id
            LEFT JOIN ' . DB_PREFIX . 'tax_rate tr ON tr.tax_rate_id = r.tax_rate_id
            LEFT JOIN jtl_connector_link l ON p.product_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_PRODUCT, $limit
        );
    }

    public static function productI18nPull($productId)
    {
        return sprintf('
            SELECT p.*, l.code
            FROM ' . DB_PREFIX . 'product_description p
            LEFT JOIN ' . DB_PREFIX . 'language l ON p.language_id = l.language_id
            WHERE p.product_id = %d',
            $productId
        );
    }

    public static function productStats()
    {
        return sprintf('
            SELECT COUNT(*)
            FROM ' . DB_PREFIX . 'product p
            LEFT JOIN jtl_connector_link l ON p.product_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_PRODUCT
        );
    }

    public static function productCategoryPull($productId)
    {
        return sprintf('
            SELECT *, CONCAT(product_id, "_", category_id) as id
            FROM ' . DB_PREFIX . 'product_to_category
            WHERE product_id = %d',
            $productId
        );
    }

    public static function productSpecialPull($productId)
    {
        return sprintf('SELECT * FROM ' . DB_PREFIX . 'product_special WHERE product_id = %d', $productId);
    }

    public static function productAttributePull($productId)
    {
        return sprintf('SELECT * FROM ' . DB_PREFIX . 'product_attribute WHERE product_id = %d', $productId);
    }

    public static function productSpecificPull($productId)
    {
        return sprintf('
            SELECT *, CONCAT(product_id, "_", filter_id) as id
            FROM ' . DB_PREFIX . 'product_filter
            WHERE product_id = %d',
            $productId
        );
    }

    public static function productSetCover($image, $productId)
    {
        return sprintf('
            UPDATE ' . DB_PREFIX . 'product
            SET image = "%s"
            WHERE product_id = %d',
            $image, $productId
        );
    }

    public static function productAddImage($productId, $image, $sortOrder)
    {
        return sprintf('
            INSERT INTO ' . DB_PREFIX . 'product_image (product_id, image, sort_order)
            values (%d, "%s", %d)',
            $productId, $image, $sortOrder
        );
    }
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Attribute">
    public static function attributeId($languageId, $name)
    {
        return sprintf('
            SELECT a.attribute_id
            FROM ' . DB_PREFIX . 'attribute a
            LEFT JOIN ' . DB_PREFIX . 'attribute_description ad ON a.attribute_id = ad.attribute_id
            WHERE ad.language_id = %d AND ad.name = "%s"',
            $languageId, $name
        );
    }
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Category">
    public static function categoryPull($limit)
    {
        return sprintf('
            SELECT c.*
            FROM ' . DB_PREFIX . 'category c
            LEFT JOIN jtl_connector_link l ON c.category_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_CATEGORY, $limit
        );
    }

    public static function categoryStats()
    {
        return sprintf('
            SELECT COUNT(*)
            FROM ' . DB_PREFIX . 'category c
            LEFT JOIN jtl_connector_link l ON c.category_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_CATEGORY
        );
    }

    public static function categoryI18n($categoryId)
    {
        return sprintf('
            SELECT c.*, l.code
            FROM ' . DB_PREFIX . 'category_description c
            LEFT JOIN ' . DB_PREFIX . 'language l ON c.language_id = l.language_id
            WHERE c.category_id = %d',
            $categoryId
        );
    }
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Cross Selling">
    public static function crossSellingPull( $limit)
    {
        return sprintf('
            SELECT DISTINCT pr.product_id
            FROM ' . DB_PREFIX . 'product_related pr
            LEFT JOIN jtl_connector_link l ON CONCAT_WS("_", pr.product_id, pr.related_id) = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
             IdentityLinker::TYPE_CROSSSELLING, $limit
        );
    }

    public static function crossSellingDelete($productId)
    {
        return sprintf('DELETE FROM ' . DB_PREFIX . 'product_related WHERE product_id = %d', $productId);
    }

    public static function crossSellingStats()
    {
        return sprintf('
            SELECT COUNT(DISTINCT(pr.product_id))
            FROM ' . DB_PREFIX . 'product_related pr
            LEFT JOIN jtl_connector_link l ON CONCAT_WS("_", pr.product_id, pr.related_id) = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            $id, IdentityLinker::TYPE_CROSSSELLING
        );
    }

    public static function crossSellingPush($productId, $relatedId)
    {
        return sprintf('
            INSERT INTO ' . DB_PREFIX . 'product_related (product_id, related_id)
            VALUES (%d, %d)',
            $productId, $relatedId
        );
    }

    public static function crossSellingItemPull($productId)
    {
        return sprintf('SELECT related_id FROM ' . DB_PREFIX . 'product_related  WHERE product_id = %d', $productId);
    }
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Customer">
    public static function customerPull($limit)
    {
        return sprintf('
            SELECT c.*, a.company, a.address_1, a.city, a.postcode, a.country_id, co.iso_code_2, co.name
            FROM ' . DB_PREFIX . 'customer c
            LEFT JOIN ' . DB_PREFIX . 'address a ON c.address_id = a.address_id
            LEFT JOIN ' . DB_PREFIX . 'country co ON a.country_id = co.country_id
            LEFT JOIN jtl_connector_link l ON c.customer_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_CUSTOMER, $limit
        );
    }

    public static function customerStats()
    {
        return sprintf('
            SELECT COUNT(*)
            FROM ' . DB_PREFIX . 'customer c
            LEFT JOIN jtl_connector_link l ON c.customer_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_CUSTOMER
        );
    }
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Image">
    public static function imageProductsPull($limit)
    {
        return sprintf('
            SELECT pi.image, pi.sort_order, CONCAT("p_", pi.product_id, "_", pi.product_image_id) as id, pi.product_id as
            foreign_key
            FROM ' . DB_PREFIX . 'product_image pi
            LEFT JOIN jtl_connector_link l ON l.endpointId = CONCAT("p_", pi.product_id, "_", pi.product_image_id)
            AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_IMAGE, $limit
        );
    }

    public static function imageProductCoverPull($limit)
    {
        return sprintf('
            SELECT p.image, 0 as sort_order, CONCAT("p_", p.product_id) as id, p.product_id as foreign_key
            FROM ' . DB_PREFIX . 'product p
            LEFT JOIN jtl_connector_link l ON l.endpointId = CONCAT("p_", p.product_id) AND l.type = %d
            WHERE l.hostId IS NULL AND p.image IS NOT NULL AND p.image != ""
            LIMIT %d',
            IdentityLinker::TYPE_IMAGE, $limit
        );
    }

    public static function imageCategoryPull($limit)
    {
        return sprintf('
            SELECT c.image, c.sort_order, CONCAT("c_", c.category_id) as id, c.category_id as foreign_key
            FROM ' . DB_PREFIX . 'category c
            LEFT JOIN jtl_connector_link l ON l.endpointId = CONCAT("c_", c.category_id) AND l.type = %d
            WHERE l.hostId IS NULL AND c.image IS NOT NULL AND c.image != ""
            LIMIT %d',
            IdentityLinker::TYPE_IMAGE, $limit
        );
    }

    public static function imageManufacturerPull($limit)
    {
        return sprintf('
            SELECT m.image, m.sort_order, CONCAT("m_", m.manufacturer_id) as id, m.manufacturer_id as foreign_key
            FROM ' . DB_PREFIX . 'manufacturer m
            LEFT JOIN jtl_connector_link l ON l.endpointId = CONCAT("m_", m.manufacturer_id) AND l.type = %d
            WHERE l.hostId IS NULL AND m.image IS NOT NULL AND m.image != ""
            LIMIT %d',
            IdentityLinker::TYPE_IMAGE, $limit
        );
    }

    public static function imageProductVariationValuePull($limit)
    {
        return sprintf('
            SELECT ov.image, ov.sort_order, CONCAT("pvv_", ov.option_value_id) as id, ov.option_value_id as foreign_key
            FROM ' . DB_PREFIX . 'option_value ov
            LEFT JOIN jtl_connector_link l ON l.endpointId = CONCAT("pvv_", ov.option_value_id) AND l.type = %d
            WHERE l.hostId IS NULL AND ov.image IS NOT NULL AND ov.image != ""
            LIMIT %d',
            IdentityLinker::TYPE_IMAGE, $limit
        );
    }

    public static function imageCategoryPush($image, $categoryId)
    {
        return sprintf('
            UPDATE ' . DB_PREFIX . 'category SET image = "%s" WHERE category_id = %d',
            $image, $categoryId
        );
    }

    public static function imageManufacturerPush($image, $manufacturerId)
    {
        return sprintf('
            UPDATE ' . DB_PREFIX . 'manufacturer SET image = "%s" WHERE manufacturer_id = %d',
            $image, $manufacturerId
        );
    }

    public static function imageProductVariationValuePush($image, $productVariationValueId)
    {
        return sprintf('
            UPDATE ' . DB_PREFIX . 'option_value SET image = "%s" WHERE option_value_id = %d',
            $image, $productVariationValueId
        );
    }

    public static function imageProductDelete($imageId)
    {
        return sprintf('DELETE FROM ' . DB_PREFIX . 'product_image WHERE product_image_id = %d', $imageId);
    }
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Manufacturer">
    public static function manufacturerPull($limit)
    {
        return sprintf('
            SELECT m.*
            FROM ' . DB_PREFIX . 'manufacturer m
            LEFT JOIN jtl_connector_link l ON m.manufacturer_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_MANUFACTURER, $limit
        );
    }

    public static function manufacturerStats()
    {
        return sprintf('
            SELECT COUNT(*)
            FROM ' . DB_PREFIX . 'manufacturer m
            LEFT JOIN jtl_connector_link l ON m.manufacturer_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_MANUFACTURER
        );
    }
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Tax">
    public static function taxRatePull()
    {
        return 'SELECT * FROM ' . DB_PREFIX . 'tax_rate';
    }

    public static function taxClassId($rate)
    {
        return sprintf('
            SELECT r.tax_class_id
            FROM ' . DB_PREFIX . 'tax_rule r
            LEFT JOIN ' . DB_PREFIX . 'tax_rate tr ON tr.tax_rate_id = r.tax_rate_id
            WHERE tr.rate = %d',
            $rate
        );
    }

    public static function taxClassPull()
    {
        return 'SELECT * FROM ' . DB_PREFIX . 'tax_class';
    }

    public static function taxRate($orderId)
    {
        return sprintf('
            SELECT tr.rate
            FROM ' . DB_PREFIX . 'order_total ot
            LEFT JOIN ' . DB_PREFIX . 'tax_rate tr ON tr.name = ot.title
            WHERE ot.code = "tax" AND ot.order_id = %d',
            $orderId
        );
    }

    public static function taxZonePull()
    {
        return 'SELECT * FROM ' . DB_PREFIX . 'geo_zone';
    }

    public static function taxZoneCountryPull($geoZoneId)
    {
        return sprintf('
            SELECT c.iso_code_2, ztgz.geo_zone_id
            FROM ' . DB_PREFIX . 'zone_to_geo_zone ztgz
            LEFT JOIN ' . DB_PREFIX . 'country c ON c.country_id = ztgz.country_id
            WHERE ztgz.geo_zone_id = %d',
            $geoZoneId
        );
    }
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Specific">
    public static function specificPull($limit)
    {
        return sprintf('
            SELECT *
            FROM ' . DB_PREFIX . 'filter_group fg
            LEFT JOIN jtl_connector_link l ON fg.filter_group_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_SPECIFIC, $limit
        );
    }

    public static function specificI18nPull($specificId)
    {
        return sprintf('
            SELECT fgd.*, l.code
            FROM ' . DB_PREFIX . 'filter_group_description fgd
            LEFT JOIN ' . DB_PREFIX . 'language l ON fgd.language_id = l.language_id
            WHERE fgd.filter_group_id = %d',
            $specificId
        );
    }

    public static function specificValuePull($specificId)
    {
        return sprintf('
            SELECT *
            FROM ' . DB_PREFIX . 'filter
            WHERE filter_group_id = %d',
            $specificId
        );
    }

    public static function specificValueI18nPull($specificValueId)
    {
        return sprintf('
            SELECT fd.*, l.code
            FROM ' . DB_PREFIX . 'filter_description fd
            LEFT JOIN ' . DB_PREFIX . 'language l ON fd.language_id = l.language_id
            WHERE fd.filter_id = %d',
            $specificValueId
        );
    }

    public static function specificValueI18nPush($specificId, $specificValueId, $languageId, $name)
    {
        return sprintf('
            INSERT INTO ' . DB_PREFIX . 'filter_description (filter_id, language_id, filter_group_id, name)
            VALUE (%d, %d, %d, "%s")',
            $specificValueId, $languageId, $specificId, $name
        );
    }

    public static function specificValuePush($specificValueId, $sortOrder)
    {
        return sprintf('
            INSERT INTO ' . DB_PREFIX . 'filter (filter_group_id, sort_order)
            VALUES (%d, %d)',
            $specificValueId, $sortOrder
        );
    }

    public static function specificValueUpdate($specificValueId, $sortOrder)
    {
        return sprintf('
            UPDATE ' . DB_PREFIX . 'filter SET sort_order = %d WHERE filter_group_id = %d',
            $sortOrder, $specificValueId
        );
    }

    public static function specificStats()
    {
        return sprintf('
            SELECT COUNT(*)
            FROM ' . DB_PREFIX . 'filter_group fg
            LEFT JOIN jtl_connector_link l ON fg.filter_group_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_SPECIFIC
        );
    }

    public static function productSpecificPush($categoryId, $specificValueId)
    {
        return sprintf('
            INSERT INTO ' . DB_PREFIX . 'category_filter (category_id, filter_id)
            VALUES (%d, %d)',
            $categoryId, $specificValueId
        );
    }

    public static function productSpecificFind($categoryId, $specificValueId)
    {
        return sprintf('
            SELECT COUNT(*)
            FROM ' . DB_PREFIX . 'category_filter
            WHERE category_id = %d AND filter_id = %d',
            $categoryId, $specificValueId
        );
    }
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Measurement Unit">
    public static function measurementUnitLengthsPull()
    {
        return 'SELECT CONCAT("l_", length_class_id) as id, value FROM ' . DB_PREFIX . 'length_class';
    }

    public static function measurementUnitLengthsI18nPull($lengthClassId)
    {
        return sprintf('
            SELECT CONCAT("l_", lcd.length_class_id) as length_class_id, lcd.title, l.code
            FROM ' . DB_PREFIX . 'length_class_description lcd
            LEFT JOIN ' . DB_PREFIX . 'language l ON lcd.language_id = l.language_id
            WHERE lcd.length_class_id = %d',
            $lengthClassId
        );
    }

    public static function measurementUnitWeightsPull()
    {
        return 'SELECT CONCAT("w_", weight_class_id) as id, value FROM ' . DB_PREFIX . 'weight_class';
    }

    public static function measurementUnitWeightsI18nPull($weightClassId)
    {
        return sprintf('
            SELECT CONCAT("w_", wcd.weight_class_id) as weight_class_id, wcd.title, l.code
            FROM ' . DB_PREFIX . 'weight_class_description wcd
            LEFT JOIN ' . DB_PREFIX . 'language l ON wcd.language_id = l.language_id
            WHERE wcd.weight_class_id = %d',
            $weightClassId
        );
    }

    public static function measurementUnitLengthId($unit)
    {
        return sprintf('
            SELECT length_class_id
            FROM ' . DB_PREFIX . 'length_class_description
            WHERE unit = "%s"',
            $unit
        );
    }

    public static function measurementUnitWeightId($unit)
    {
        return sprintf('
            SELECT weight_class_id
            FROM ' . DB_PREFIX . 'weight_class_description
            WHERE unit = "%s"',
            $unit
        );
    }
    // </editor-fold>
}