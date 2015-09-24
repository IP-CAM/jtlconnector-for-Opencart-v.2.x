<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\Model\ProductVariation as ProductVariationModel;
use jtl\Connector\Model\ProductVariationI18n;
use jtl\Connector\Model\ProductVariationValueI18n;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Mapper\Product\ProductVariationValue as ProductVariationValueMapper;
use jtl\Connector\OpenCart\Utility\SQLs;
use jtl\Connector\OpenCart\Utility\Utils;

class ProductVariation extends BaseController
{
    private $utils;

    public function __construct()
    {
        parent::__construct();
        $this->utils = Utils::getInstance();
    }


    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        // Do not pull checkbox as configuration items are not supported yet.
        // Do not pull file as uploads are handled extra.
        return sprintf(SQLs::PRODUCT_VARIATION_PULL, $data['product_id']);
    }

    public function pushData(ProductModel $data, &$model)
    {
        $model['product_option'] = [];
        foreach ($data->getVariations() as $variation) {
            $option = $this->mapper->toEndpoint($variation);
            $optionId = $this->buildOptionDescriptions($variation, $option);
            $this->buildOptionValues($variation, $option);
            $ocOption = $this->oc->loadAdminModel('catalog/option');
            if (is_null($optionId)) {
                $optionId = $ocOption->addOption($option);
            } else {
                $ocOption->editOption($optionId, $option);
            }
            $productOption = $this->mapper->toEndpoint($variation);
            $productOption['option_id'] = $optionId;
            $productOption['product_option_id'] = "";
            $this->buildProductOptionValues($variation, $productOption);
            $model['product_option'][] = $productOption;
        }
    }

    private function buildOptionDescriptions(ProductVariationModel $variation, &$option)
    {
        $optionId = null;
        foreach ($variation->getI18ns() as $i18n) {
            $languageId = $this->utils->getLanguageId($i18n->getLanguageISO());
            if ($languageId !== false) {
                $option['option_description'][intval($languageId)] = [
                    'name' => $i18n->getName()
                ];
            }
            if (is_null($optionId)) {
                $optionId = $this->findExistingOption($i18n, $variation->getType());
            }
        }
        return $optionId;
    }

    private function findExistingOption(ProductVariationI18n $i18n, $type)
    {
        $languageId = $this->utils->getLanguageId($i18n->getLanguageISO());
        $optionId = $this->database->queryOne(SQLs::OPTION_ID_BY_DESCRIPTION, $languageId, $i18n->getName(), $type);
        return $optionId;
    }

    private function buildOptionValues(ProductVariationModel $variation, &$option)
    {
        foreach ($variation->getValues() as $value) {
            $optionValueId = null;
            $optionValue = [
                'image' => '',
                'sort_order' => $value->getSort()
            ];
            foreach ($value->getI18ns() as $i18n) {
                $languageId = $this->utils->getLanguageId($i18n->getLanguageISO());
                if (!is_null($languageId)) {
                    $optionValue['option_value_description'][intval($languageId)] = [
                        'name' => $i18n->getName()
                    ];
                }
                if (is_null($optionValueId)) {
                    $optionValueId = $this->findExistingOptionValue($i18n);
                }
            }
            $optionValue['option_value_id'] = $optionValueId;
            $option['option_value'][] = $optionValue;
        }
    }

    private function findExistingOptionValue(ProductVariationValueI18n $i18n)
    {
        $languageId = $this->utils->getLanguageId($i18n->getLanguageISO());
        $optionValueId = $this->database->queryOne(SQLs::OPTION_VALUE_ID_BY_DESCRIPTION, $languageId, $i18n->getName());
        return $optionValueId;
    }

    private function buildProductOptionValues(ProductVariationModel $variation, &$productOption)
    {
        if (in_array($variation->getType(),
            [ProductVariationModel::TYPE_FREE_TEXT, ProductVariationModel::TYPE_FREE_TEXT_OBLIGATORY])) {
            $productOption['product_option_value']['value'] = "";
        } else {
            $this->buildMultipleProductOptionValue($variation, $productOption);
        }
    }

    private function buildMultipleProductOptionValue(ProductVariationModel $variation, &$productOption)
    {
        foreach ($variation->getValues() as $value) {
            $optionValueId = null;
            $mapper = new ProductVariationValueMapper();
            $productOptionValue = $mapper->toEndpoint($value);
            foreach ($value->getI18ns() as $i18n) {
                $optionValueId = $this->findExistingOptionValue($i18n);
                if (!is_null($optionValueId)) {
                    break;
                }
            }
            $productOptionValue['option_value_id'] = $optionValueId;
            $productOption['product_option_value'][] = $productOptionValue;
        }
    }
}
