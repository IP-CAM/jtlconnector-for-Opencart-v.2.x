<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\OpenCart;
use jtl\Connector\OpenCart\Utility\Utils;

class ProductAttr extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
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

    public function pushData($data, &$model)
    {
        $model['product_attribute'] = [];
        foreach ($data->getAttributes() as $attr) {
            $productId = $attr->getProductId()->getEndpoint();
            list($values, $descriptions) = $this->buildI18ns($attr);
            $attributeId = $this->getOrCreateAttribute($productId, $descriptions);
            $model['product_attribute'][] = [
                'attribute_id' => $attributeId,
                'product_attribute_description' => $values
            ];
        }
    }

    private function buildI18ns($attr)
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

    private function getOrCreateAttribute($productId, $descriptions)
    {
        $attributeId = $this->database->query(sprintf('
            SELECT attribute_id
            FROM oc_product_attribute
            WHERE product_id = %s',
            $productId
        ));
        if (is_null($attributeId)) {
            $attribute = OpenCart::getInstance()->loadModel('catalog/attribute');
            $attributeId = $attribute->addAttribute([
                'sort_order' => 0,
                'attribute_group_id' => 0,
                'attribute_description' => $descriptions
            ]);
        }
        return $attributeId;
    }
}