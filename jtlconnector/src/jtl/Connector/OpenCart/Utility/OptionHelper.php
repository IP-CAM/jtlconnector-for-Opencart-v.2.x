<?php

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Core\Utilities\Singleton;

class OptionHelper extends Singleton
{
    /**
     * @var Db
     */
    private $database;
    /**
     * @var Utils
     */
    private $utils;

    public function __construct()
    {
        $this->database = Db::getInstance();
        $this->utils = Utils::getInstance();
    }

    public function buildOptionDescriptions($variation)
    {
        $optionId = null;
        $descriptions = [];
        foreach ($variation->getI18ns() as $i18n) {
            $languageId = $this->utils->getLanguageId($i18n->getLanguageISO());
            if ($languageId !== false) {
                $descriptions[intval($languageId)] = [
                    'name' => $i18n->getName()
                ];
            }
            if (is_null($optionId)) {
                $optionId = $this->findExistingOption($i18n, $variation->getType());
            }
        }
        return array($optionId, $descriptions);
    }

    public function findExistingOption($i18n, $type)
    {
        $languageId = $this->utils->getLanguageId($i18n->getLanguageISO());
        $query = SQLs::optionId($languageId, $i18n->getName(), $type);
        $optionId = $this->database->queryOne($query);
        return $optionId;
    }

    public function buildOptionValues($variation, $optionId)
    {
        $optionValues = [];
        foreach ($variation->getValues() as $value) {
            $optionValueId = null;
            $optionValue = [
                'image' => '',
                'sort_order' => $value->getSort(),
                'option_value_description' => []
            ];
            $descriptions = $this->buildOptionValueDescription($value, $optionId, $optionValueId);
            $optionValue['option_value_description'] = $descriptions;
            $optionValue['option_value_id'] = $optionValueId;
            $optionValues[] = $optionValue;
        }
        return $optionValues;
    }

    public function findExistingOptionValue($i18n, $optionId)
    {
        $languageId = $this->utils->getLanguageId($i18n->getLanguageISO());
        $query = SQLs::optionValueId($languageId, $i18n->getName(), $optionId);
        $optionValueId = $this->database->queryOne($query);
        return $optionValueId;
    }

    public function deleteObsoleteOptions($productId)
    {
        $ocOption = OpenCart::getInstance()->loadAdminModel('catalog/option');
        $result = $this->database->query(SQLs::obsoleteOptions());
        foreach ($result as $optionId) {
            $ocOption->deleteOption($optionId['option_id']);
        }
    }

    private function buildOptionValueDescription($value, $optionId, &$optionValueId)
    {
        $descriptions = [];
        foreach ($value->getI18ns() as $i18n) {
            $languageId = $this->utils->getLanguageId($i18n->getLanguageISO());
            if (!is_null($languageId)) {
                $descriptions[intval($languageId)] = [
                    'name' => $i18n->getName()
                ];
                if (is_null($optionValueId)) {
                    $optionValueId = $this->findExistingOptionValue($i18n, $optionId);
                }
            }
        }
        return $descriptions;
    }
}