<?php

class ModelExtensionModuleJtlConnector extends Model
{
    public function createSchema()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS jtl_connector_link (
                endpointId CHAR(64) NOT NULL,
                hostId INT(10) NOT NULL,
                type INT(10),
                PRIMARY KEY (endpointId, hostId, type)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS jtl_connector_checksum (
                endpointId INT(10) UNSIGNED NOT NULL,
                type TINYINT UNSIGNED NOT NULL,
                checksum VARCHAR(255) NOT NULL,
                PRIMARY KEY (endpointId)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS jtl_connector_category_level (
                category_id int(11) NOT NULL,
                level int(10) unsigned NOT NULL,
                PRIMARY KEY (`category_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
    }
}