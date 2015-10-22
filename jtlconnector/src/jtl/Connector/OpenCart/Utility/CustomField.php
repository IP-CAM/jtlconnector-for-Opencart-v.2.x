<?php

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Core\Utilities\Singleton;

class CustomField extends Singleton
{
    private $database;
    private $ocVersion;

    function __construct()
    {
        $this->database = Db::getInstance();
        $this->ocVersion = OpenCart::getInstance()->getVersion();
    }

    public function vatNumber(array $data)
    {
        $valueId = $this->database->queryOne(SQLs::freeFieldVatId());
        if (!is_null($valueId)) {
            if (version_compare($this->ocVersion, '2.1.0.0', '<')) {
                $customFields = unserialize($data['custom_field']);
            } else {
                $customFields = json_decode($data['custom_field'], true);
            }
            return (isset($customFields[$valueId])) ? $customFields[$valueId] : '';
        }
        return '';
    }

    public function title(array $data)
    {
        $freeFieldId = $this->database->queryOne(SQLs::freeFieldTitleId());
        if (!is_null($freeFieldId)) {
            if (version_compare($this->ocVersion, '2.1.0.0', '<')) {
                $customFields = unserialize($data['custom_field']);
            } else {
                $customFields = json_decode($data['custom_field'], true);
            }
            if (isset($customFields[$freeFieldId])) {
                return $this->database->queryOne(SQLs::freeFieldValue($customFields[$freeFieldId]));
            } else {
                return '';
            }
        }
        return '';
    }

    public function salutation(array $data)
    {
        $freeFieldId = $this->database->queryOne(SQLs::freeFieldSalutationId());
        if (!is_null($freeFieldId)) {
            if (version_compare($this->ocVersion, '2.1.0.0', '<')) {
                $customFields = unserialize($data['custom_field']);
            } else {
                $customFields = json_decode($data['custom_field'], true);
            }
            if (isset($customFields[$freeFieldId])) {
                return $this->database->queryOne(SQLs::freeFieldValue($customFields[$freeFieldId]));
            } else {
                return '';
            }
        }
        return '';
    }

    /**
     * @return CustomField
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }
}