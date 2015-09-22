<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\Model\ProductAttr as ProductAttribute;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\OpenCart;
use jtl\Connector\OpenCart\Utility\Utils;

class ProductAttr extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT *
            FROM oc_product_attribute
            WHERE product_id = %d',
            $data['product_id']
        );
    }

    public function pushData(ProductModel $data, &$model)
    {
        $model['product_attribute'] = [];
        foreach ($data->getAttributes() as $attr) {
            list($values, $descriptions) = $this->buildI18ns($attr);
            $attributeId = $this->getOrCreateAttribute($descriptions);
            $model['product_attribute'][] = [
                'attribute_id' => $attributeId,
                'product_attribute_description' => $values
            ];
        }
    }

    /**
     * Build for each of the internationalized product attributes the OpenCart specific attribute_description =>
     * descriptions and product_attribute_description => values.
     */
    private function buildI18ns(ProductAttribute $attr)
    {
        $values = [];
        $descriptions = [];
        foreach ($attr->getI18ns() as $i18n) {
            $languageId = Utils::getInstance()->getLanguageId($i18n->getLanguageISO());
            if ($languageId !== false) {
                $values[intval($languageId)] = [
                    'text' => $i18n->getValue()
                ];
                $descriptions[intval($languageId)] = [
                    'name' => $i18n->getName()
                ];
            }
        }
        return array($values, $descriptions);
    }

    /**
     * Check if there is already an attribute existing based on its descriptions.
     */
    private function getOrCreateAttribute(array $descriptions)
    {
        $attributeId = null;
        foreach ($descriptions as $languageId => $desc) {
            $attributeId = $this->database->queryOne(sprintf('
                SELECT a.attribute_id
                FROM oc_attribute a
                LEFT JOIN oc_attribute_description ad ON a.attribute_id = ad.attribute_id
                WHERE ad.language_id = %d AND ad.name = "%s"',
                $languageId, $desc['name']
            ));
            if (!is_null($attributeId)) {
                break;
            }
        }
        if (is_null($attributeId)) {
            $groupId = OpenCart::getInstance()->getConfig(\ControllerModuleJtlconnector::CONFIG_ATTRIBUTE_GROUP);
            $attribute = OpenCart::getInstance()->loadModel('catalog/attribute');
            $attributeId = $attribute->addAttribute([
                'sort_order' => 0,
                'attribute_group_id' => $groupId,
                'attribute_description' => $descriptions
            ]);
        }
        return $attributeId;
    }
}