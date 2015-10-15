<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Core\Utilities\Singleton;

class Option extends Singleton
{
    private $utils;
    private $database;

    public function __construct()
    {
        $this->utils = Utils::getInstance();
        $this->database = Db::getInstance();
    }

    public function buildOptionDescriptions($variation, $type)
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
                $optionId = $this->findExistingOption($i18n, $type);
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
        if (!is_null($optionId)) {
            $optionValues = $this->addExistingOptionValues($optionValues, $optionId);
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

    public function deleteObsoleteOptions()
    {
        $ocOption = OpenCart::getInstance()->loadAdminModel('catalog/option');
        if ($ocOption instanceof \ModelCatalogOption) {
            $result = $this->database->query(SQLs::obsoleteOptions());
            foreach ($result as $optionId) {
                $ocOption->deleteOption($optionId['option_id']);
                $this->database->query(SQLs::deleteObsoleteProductOptions());
            }
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

    /**
     * As all the existing option values are deleted and the new ones added, some values can get lost if
     * a product has less colors than another.
     *
     * @param $values array All the option values of an option value.
     * @param $optionId string The option which should be handled.
     * @return array The merges array of existing and new options.
     */
    private function addExistingOptionValues($values, $optionId)
    {
        $result = $this->database->query(SQLs::optionValues($optionId));
        foreach ($result as $row) {
            $found = false;
            foreach ($values as $i => $value) {
                if ($value['option_value_id'] === $row['option_value_id']) {
                    $found = true;
                    $values[$i]['option_value_description'][intval($row['language_id'])] = [
                        'name' => $row['name']
                    ];
                }
            }
            if (!$found) {
                $value = $row;
                $value['option_value_description'][intval($row['language_id'])] = [
                    'name' => $row['name']
                ];
                $values[] = $value;
            }
        }
        return $values;
    }

    /**
     * @return Option
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }
}