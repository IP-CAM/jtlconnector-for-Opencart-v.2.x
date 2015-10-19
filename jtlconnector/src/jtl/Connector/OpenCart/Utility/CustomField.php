<?php

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Core\Utilities\Singleton;

class CustomField extends Singleton
{
    private $database;

    function __construct()
    {
        $this->database = Db::getInstance();
    }

    protected function vatNumber(array $data)
    {
        $valueId = $this->database->queryOne(SQLs::freeFieldVatId());
        if (!is_null($valueId)) {
            return json_decode($data['custom_field'])->$valueId;
        }
        return "";
    }

    protected function title(array $data)
    {
        $freeFieldId = $this->database->queryOne(SQLs::freeFieldTitleId());
        if (!is_null($freeFieldId)) {
            $valueId = json_decode($data['custom_field'])->$freeFieldId;
            return $this->database->queryOne(SQLs::freeFieldValue($valueId));
        }
        return "";
    }

    protected function salutation(array $data)
    {
        $freeFieldId = $this->database->queryOne(SQLs::freeFieldSalutationId());
        if (!is_null($freeFieldId)) {
            $valueId = json_decode($data['custom_field'])->$freeFieldId;
            return $this->database->queryOne(SQLs::freeFieldValue($valueId));
        }
        return "";
    }

    /**
     * @return CustomField
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }
}