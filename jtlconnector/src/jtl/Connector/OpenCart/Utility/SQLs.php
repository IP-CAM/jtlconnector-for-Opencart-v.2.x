<?php

namespace jtl\Connector\OpenCart\Utility;

final class SQLs
{
    // <editor-fold defaultstate="collapsed" desc="Product Variation">
    const PRODUCT_VARIATION_PULL = '
        SELECT *
        FROM oc_product_option po
        LEFT JOIN oc_option o ON po.option_id = o.option_id
        WHERE po.product_id = %d AND o.type NOT IN ("checkbox", "file")';
    const PRODUCT_VARIATION_I18N_PULL = '
        SELECT po.product_option_id, od.name, l.code
        FROM oc_option_description od
        LEFT JOIN oc_product_option po ON po.option_id = od.option_id
        LEFT JOIN oc_language l ON l.language_id = od.language_id
        WHERE po.product_option_id = %d';
    const PRODUCT_VARIATION_VALUE_PULL = 'SELECT * FROM oc_product_option_value WHERE product_option_id = %d';
    const PRODUCT_VARIATION_VALUE_I18N_PULL = '
        SELECT pov.product_option_value_id, ovd.name, l.code
        FROM oc_product_option_value pov
        LEFT JOIN oc_option_value_description ovd ON pov.option_value_id = ovd.option_value_id
        LEFT JOIN oc_language l ON ovd.language_id = l.language_id
        WHERE pov.product_option_value_id = %d';
    const FILE_UPLOAD_PULL = '
        SELECT *
        FROM oc_product_option po
        LEFT JOIN oc_option o ON po.option_id = o.option_id
        LEFT JOIN oc_option_description od ON od.option_id = o.option_id
        WHERE o.type = "file"
        LIMIT %d';
    const FILE_UPLOAD_PUSH = 'INSERT INTO oc_product_option (product_id, option_id, required) VALUES (%d, %d, %d)';
    const FILE_UPLOAD_DELETE = 'DELETE FROM oc_product_option WHERE product_option_id = %d';
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Option">
    const OPTION_ID_BY_DESCRIPTION = '
        SELECT o.option_id
        FROM oc_option o
        LEFT JOIN oc_option_description od ON o.option_id = od.option_id
        WHERE od.language_id = %d AND od.name = "%s" AND o.type = "%s"';
    const OPTION_VALUE_ID_BY_DESCRIPTION = '
        SELECT ov.option_value_id
        FROM oc_option_value ov LEFT JOIN oc_option_value_description ovd ON ovd.option_value_id = ov.option_value_id
        WHERE ovd.language_id = %d AND ovd.name = "%s"';
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Module">
    const MODULE_FEATURED_WAWI = 'SELECT module_id FROM oc_module WHERE code = "featured" AND name = "Featured - Wawi"';
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Global">
    const CUSTOMER_GROUP_PULL = 'SELECT * FROM oc_customer_group';
    const CUSTOMER_GROUP_I18N_PULL = '
        SELECT c.*, l.code
        FROM oc_customer_group_description c
        LEFT JOIN oc_language l ON c.language_id = l.language_id
        WHERE c.customer_group_id = %d';
    const CURRENCY_PULL = 'SELECT * FROM oc_currency WHERE status = 1';
    const CURRENCY_UPDATE = 'UPDATE oc_currency SET value = %d WHERE currency_id = %d';
    const GLOBAL_DATA_STATS = 'SELECT
        (SELECT COUNT(*) FROM oc_currency) +
        (SELECT COUNT(*) FROM oc_customer_group) +
        (SELECT COUNT(*) FROM oc_language) +
        (SELECT COUNT(*) FROM oc_tax_rate)';
    const LANGUAGE_PULL = 'SELECT * FROM oc_language WHERE status = 1';
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Customer Order">
    const CUSTOMER_ORDER_PULL = '
        SELECT o.*, l.code, c.iso_code_3
        FROM oc_order o
        LEFT JOIN oc_language l ON o.language_id = l.language_id
        LEFT JOIN oc_country c ON o.payment_country_id = c.country_id
        LEFT JOIN jtl_connector_link cl ON o.order_id = cl.endpointId AND cl.type = %d
        WHERE cl.hostId IS NULL
        LIMIT %d';
    const CUSTOMER_ORDER_STATS = '
        SELECT COUNT(*)
		FROM oc_order o
		LEFT JOIN jtl_connector_link l ON o.order_id = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL';
    const CUSTOMER_ORDER_SHIPPING_STATUS = '
        SELECT os.name, oh.date_added
        FROM oc_order o
        LEFT JOIN oc_language l ON o.language_id = l.language_id
        LEFT JOIN oc_order_status os ON os.order_status_id = o.order_status_id AND os.language_id = l.language_id
        LEFT JOIN oc_order_history oh ON oh.order_status_id = o.order_status_id
        WHERE oh.order_id = %d
        ORDER BY oh.date_added
        LIMIT 1';
    const CUSTOMER_ORDER_SHIPPING_STATUS_ID = '
        SELECT oh.order_status_id
        FROM oc_order_status os
        LEFT JOIN oc_order_history oh ON oh.order_status_id = os.order_status_id
        WHERE oh.order_id = %d
        ORDER BY oh.date_added
        LIMIT 1';
    const CUSTOMER_ORDER_PAYMENT_STATUS = '
        SELECT oh.comment, oh.date_added
        FROM oc_order o
        LEFT JOIN oc_language l ON o.language_id = l.language_id
        LEFT JOIN oc_order_history oh ON oh.order_status_id = o.order_status_id
        WHERE oh.order_id = %d AND oh.comment != "" AND oh.comment IN (%s)
        ORDER BY oh.date_added
        LIMIT 1';
    const CUSTOMER_ORDER_PRODUCTS = '
        SELECT op.*, p.sku
        FROM oc_order_product op
        LEFT JOIN oc_product p ON p.product_id = op.product_id
        WHERE op.order_id = %d';
    const CUSTOMER_ORDER_SHIPPINGS = '
        SELECT *
        FROM oc_order_total
        WHERE code = "shipping" AND order_id = %d';
    const CUSTOMER_ORDER_DISCOUNTS = '
        SELECT *
        FROM oc_order_total
        WHERE code IN ("coupon", "voucher") AND order_id = %d';
    const CUSTOMER_ORDER_ITEM_VARIATION = '
        SELECT oo.*, u.name AS filename, pov.price_prefix, pov.price
        FROM oc_order_option oo
        LEFT JOIN oc_product_option po ON oo.product_option_id = po.product_option_id
        LEFT JOIN oc_option o ON o.option_id = po.option_id
        LEFT JOIN oc_upload u ON u.code = oo.value
        LEFT JOIN oc_product_option_value pov ON pov.product_option_value_id = oo.product_option_value_id
        WHERE oo.order_id = %d
        ORDER BY o.sort_order';
    const CUSTOMER_ORDER_STATUS = '
        UPDATE oc_order
        SET order_status_id = %d, date_modified = NOW()
        WHERE order_id = %d';
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Product">
    const PRODUCT_PULL = '
        SELECT p.*, tr.rate
        FROM oc_product p
        LEFT JOIN oc_tax_class tc ON p.tax_class_id = tc.tax_class_id
        LEFT JOIN oc_tax_rule r ON r.tax_class_id = tc.tax_class_id
        LEFT JOIN oc_tax_rate tr ON tr.tax_rate_id = r.tax_rate_id
        LEFT JOIN jtl_connector_link l ON p.product_id = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL
        LIMIT %d';
    const PRODUCT_I18N_PULL = '
        SELECT p.*, l.code
        FROM oc_product_description p
        LEFT JOIN oc_language l ON p.language_id = l.language_id
        WHERE p.product_id = %d';
    const PRODUCT_STATS = '
        SELECT COUNT(*)
        FROM oc_product p
        LEFT JOIN jtl_connector_link l ON p.product_id = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL';
    const PRODUCT_CATEGORY_PULL = '
        SELECT *, CONCAT(product_id, "_", category_id) as id
        FROM oc_product_to_category
        WHERE product_id = %d';
    const PRODUCT_SPECIAL_PULL = 'SELECT * FROM oc_product_special WHERE product_id = %d';
    const PRODUCT_ATTRIBUTE_PULL = 'SELECT * FROM oc_product_attribute WHERE product_id = %d';
    const PRODUCT_SET_COVER = 'UPDATE oc_product SET image = "%s" WHERE product_id = %d';
    const PRODUCT_RESET_COVER = 'UPDATE oc_product SET image = NULL WHERE product_id = %d';
    const PRODUCT_ADD_IMAGE = '
        INSERT INTO oc_product_image (product_id, image, sort_order)
        values (%d, "%s", %d)';
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Attribute">
    const ATTRIBUTE_ID_BY_DESCRIPTION = '
        SELECT a.attribute_id
        FROM oc_attribute a
        LEFT JOIN oc_attribute_description ad ON a.attribute_id = ad.attribute_id
        WHERE ad.language_id = %d AND ad.name = "%s"';
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Category">
    const CATEGORY_PULL = '
        SELECT c.*
        FROM oc_category c
        LEFT JOIN jtl_connector_link l ON c.category_id = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL
        LIMIT %d';
    const CATEGORY_STATS = '
        SELECT COUNT(*)
        FROM oc_category c
        LEFT JOIN jtl_connector_link l ON c.category_id = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL';
    const CATEGORY_I18N_PULL = '
        SELECT c.*, l.code
        FROM oc_category_description c
        LEFT JOIN oc_language l ON c.language_id = l.language_id
        WHERE c.category_id = %d';
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Cross Selling">
    const CROSS_SELLING_PULL = '
        SELECT DISTINCT pr.product_id
        FROM oc_product_related pr
        LEFT JOIN jtl_connector_link l ON %s = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL
        LIMIT %d';
    const CROSS_SELLING_DELETE = 'DELETE FROM oc_product_related WHERE product_id = %d';
    const CROSS_SELLING_STATS = '
        SELECT COUNT(DISTINCT(pr.product_id))
        FROM oc_product_related pr
        LEFT JOIN jtl_connector_link l ON %s = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL';
    const CROSSELLING_ADD = 'INSERT INTO oc_product_related (product_id, related_id) VALUES (%d, %d)';
    const CROSS_SELLING_ITEM_PULL = 'SELECT related_id FROM oc_product_related  WHERE product_id = %d';
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Customer">
    const CUSTOMER_PULL = '
        SELECT c.*, a.company, a.address_1, a.city, a.postcode, a.country_id, co.iso_code_2, co.name
        FROM oc_customer c
        LEFT JOIN oc_address a ON c.address_id = a.address_id
        LEFT JOIN oc_country co ON a.country_id = co.country_id
        LEFT JOIN jtl_connector_link l ON c.customer_id = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL
        LIMIT %d';
    const CUSTOMER_DELETE = '
        SELECT COUNT(*)
        FROM oc_customer c
        LEFT JOIN jtl_connector_link l ON c.customer_id = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL';
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Image">
    const IMAGE_PRODUCT_PULL_EXTRA = '
        SELECT pi.image, pi.sort_order, CONCAT("p_", pi.product_id, "_", pi.product_image_id) as id, pi.product_id as
        foreign_key
        FROM oc_product_image pi
        LEFT JOIN jtl_connector_link l ON l.endpointId = CONCAT("p_", pi.product_id, "_", pi.product_image_id)
        AND l.type = %d
        WHERE l.hostId IS NULL
        LIMIT %d';
    const IMAGE_PRODUCT_PULL_COVER = '
        SELECT p.image, 0 as sort_order, CONCAT("p_", p.product_id) as id, p.product_id as foreign_key
        FROM oc_product p
        LEFT JOIN jtl_connector_link l ON l.endpointId = CONCAT("p_", p.product_id) AND l.type = %d
        WHERE l.hostId IS NULL AND p.image IS NOT NULL AND p.image !=""';
    const IMAGE_CATEGORY_PULL = '
        SELECT c.image, c.sort_order, CONCAT("c_", c.category_id) as id, c.category_id as foreign_key
        FROM oc_category c
        LEFT JOIN jtl_connector_link l ON l.endpointId = CONCAT("c_", c.category_id) AND l.type = %d
        WHERE l.hostId IS NULL AND c.image IS NOT NULL AND c.image != ""
        LIMIT %d';
    const IMAGE_MANUFACTURER_PULL = '
        SELECT m.image, m.sort_order, CONCAT("m_", m.manufacturer_id) as id, m.manufacturer_id as foreign_key
        FROM oc_manufacturer m
        LEFT JOIN jtl_connector_link l ON l.endpointId = CONCAT("m_", m.manufacturer_id) AND l.type = %d
        WHERE l.hostId IS NULL AND m.image IS NOT NULL AND m.image != ""
        LIMIT %d';
    const IMAGE_CATEGORY_PUSH = 'UPDATE oc_category SET image = "%s" WHERE category_id = %d';
    const IMAGE_MANUFACTURER_PUSH = 'UPDATE oc_manufacturer SET image = "%s" WHERE manufacturer_id = %d';
    const IMAGE_PRODUCT_DELETE = 'DELETE FROM oc_product_image WHERE product_image_id = %d';
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Manufacturer">
    const MANUFACTURER_PULL = '
        SELECT m.*
        FROM oc_manufacturer m
        LEFT JOIN jtl_connector_link l ON m.manufacturer_id = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL
        LIMIT %d';
    const MANUFACTURER_STATS = '
        SELECT COUNT(*)
        FROM oc_manufacturer m
        LEFT JOIN jtl_connector_link l ON m.manufacturer_id = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL';
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Tax">
    const TAX_RATE_PULL = 'SELECT * FROM oc_tax_rate';
    const TAX_CLASS_BY_RATE = '
        SELECT r.tax_class_id
        FROM oc_tax_rule r
        LEFT JOIN oc_tax_rate tr ON tr.tax_rate_id = r.tax_rate_id
        WHERE tr.rate = %d';
    const TAX_CLASS_PULL = 'SELECT * FROM oc_tax_class';
    const TAX_RATE_BY_ORDER = '
            SELECT tr.rate
            FROM oc_order_total ot
            LEFT JOIN oc_tax_rate tr ON tr.name = ot.title
            WHERE ot.code = "tax" AND ot.order_id = %d';
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Status Change">
    const STATUS_CHANGE_BY_ORDER = 'SELECT count(*) FROM oc_order WHERE order_id = %d';
    const STATUS_CHANGE_ADD = '
        INSERT INTO oc_order_history (order_id, order_status_id, notify, comment, date_added)
        VALUES (%d, %d, 0, "Payment: %s", NOW())';
    // </editor-fold>
    //// <editor-fold defaultstate="collapsed" desc="Status Change">
    const SPECIFIC_PULL = '
        SELECT *
        FROM oc_filter_group fg
        LEFT JOIN oc_filter_group_description fgd ON fgd.filter_group_id = fg.filter_group_id
        LEFT JOIN jtl_connector_link l ON fg.filter_group_id = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL
        LIMIT %d';
    const SPECIFIC_I18N_PULL = '
        SELECT fgd.*, l.code
        FROM oc_filter_group_description fgd
        LEFT JOIN oc_language l ON fgd.language_id = l.language_id
        WHERE fgd.filter_group_id = %d';
    const SPECIFIC_STATS = '
        SELECT COUNT(*)
        FROM oc_filter_group fg
        LEFT JOIN jtl_connector_link l ON fg.filter_group_id = l.endpointId AND l.type = %d
        WHERE l.hostId IS NULL';
    // </editor-fold>
}