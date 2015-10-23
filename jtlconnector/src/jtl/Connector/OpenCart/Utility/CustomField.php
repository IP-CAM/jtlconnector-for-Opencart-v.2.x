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
            $customFields = $this->parseCustomFields($data);
            return (isset($customFields[$valueId])) ? $customFields[$valueId] : '';
        }
        return '';
    }

    public function title(array $data)
    {
        $freeFieldId = $this->database->queryOne(SQLs::freeFieldTitleId());
        if (!is_null($freeFieldId)) {
            $customFields = $this->parseCustomFields($data);
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
            $customFields = $this->parseCustomFields($data);
            if (isset($customFields[$freeFieldId])) {
                return $this->database->queryOne(SQLs::freeFieldValue($customFields[$freeFieldId]));
            } else {
                return '';
            }
        }
        return '';
    }

    private function parseCustomFields(array $data)
    {
        if (version_compare($this->ocVersion, '2.0.3.1', '>')) {
            $customFields = json_decode($data['custom_field'], true);
            return $customFields;
        } else {
            $customFields = unserialize($data['custom_field']);
            return $customFields;
        }
    }

    /**
     * @return CustomField
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }
}