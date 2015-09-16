<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\ProductVariation as ProductVariationModel;
use jtl\Connector\Model\ProductVariationValueI18n;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\Utils;

class ProductVariationValue extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT *
            FROM oc_product_option_value
            WHERE product_option_id = %d',
            $data['product_option_id']
        );
    }

    public function pushData(ProductVariationModel $data, &$model)
    {
        foreach ($data->getValues() as $value) {
            $optionValueId = null;
            $endpoint = $this->mapper->toEndpoint($value);
            foreach ($value->getI18ns() as $i18n) {
                $optionValueId = $this->findExistingOptionValue($i18n);
                if (!is_null($optionValueId)) {
                    break;
                }
            }
            $endpoint['option_value_id'] = $optionValueId;
            $model['product_option_value'][] = $endpoint;
        }
    }

    private function findExistingOptionValue(ProductVariationValueI18n $i18n)
    {
        $languageId = Utils::getInstance()->getLanguageId($i18n->getLanguageISO());
        $optionValueId = $this->database->queryOne(sprintf('
            SELECT ov.option_value_id
            FROM oc_option_value ov LEFT JOIN oc_option_value_description ovd ON ovd.option_value_id = ov.option_value_id
            WHERE ovd.language_id = %d AND ovd.name = "%s"',
            $languageId, $i18n->getName()
        ));
        return $optionValueId;
    }
}
