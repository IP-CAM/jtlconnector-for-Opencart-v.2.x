<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\Model\ProductVariation as ProductVariationModel;
use jtl\Connector\Model\ProductVariationI18n;
use jtl\Connector\Model\ProductVariationValueI18n;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Mapper\Product\ProductVariationValue as ProductVariationValueMapper;
use jtl\Connector\OpenCart\Utility\OpenCart;
use jtl\Connector\OpenCart\Utility\Utils;

class ProductVariation extends BaseController
{
    private $oc;
    private $utils;

    public function __construct()
    {
        parent::__construct();
        $this->utils = Utils::getInstance();
        $this->oc = OpenCart::getInstance();
    }


    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    /**
     * Do not pull checkbox as configuration items are not supported.
     * Do not pull file as uploads are handled extra.
     */
    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT *
            FROM oc_product_option po
            LEFT JOIN oc_option o ON po.option_id = o.option_id
            WHERE po.product_id = %d AND o.type NOT IN ("checkbox", "file");',
            $data['product_id']
        );
    }

    public function pushData(ProductModel $data, &$model)
    {
        $model['product_option'] = [];
        foreach ($data->getVariations() as $variation) {
            $option = $this->mapper->toEndpoint($variation);
            $optionId = $this->buildOptionDescriptions($variation, $option);
            $this->buildOptionValues($variation, $option);
            $ocOption = $this->oc->loadModel('catalog/option');
            if (is_null($optionId)) {
                $optionId = $ocOption->addOption($option);
            } else {
                $ocOption->editOption($optionId, $option);
            }
            $productOption = $this->mapper->toEndpoint($variation);
            $productOption['option_id'] = $optionId;
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
                $optionId = $this->findExistingOption($i18n);
            }
        }
        return $optionId;
    }

    private function findExistingOption(ProductVariationI18n $i18n)
    {
        $languageId = $this->utils->getLanguageId($i18n->getLanguageISO());
        $optionId = $this->database->queryOne(sprintf('
            SELECT o.option_id
            FROM oc_option o
            LEFT JOIN oc_option_description od ON o.option_id = od.option_id
            WHERE od.language_id = %d AND od.name = "%s"',
            $languageId, $i18n->getName()
        ));
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
                if ($languageId !== false) {
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
        $optionValueId = $this->database->queryOne(sprintf('
            SELECT ov.option_value_id
            FROM oc_option_value ov LEFT JOIN oc_option_value_description ovd ON ovd.option_value_id = ov.option_value_id
            WHERE ovd.language_id = %d AND ovd.name = "%s"',
            $languageId, $i18n->getName()
        ));
        return $optionValueId;
    }

    private function buildProductOptionValues(ProductVariationModel $variation, &$productOption)
    {
        if (in_array($variation->getType(),
            [ProductVariationModel::TYPE_FREE_TEXT, ProductVariationModel::TYPE_FREE_TEXT_OBLIGATORY])) {
            $this->buildSingleProductOptionValue($variation, $productOption);
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

    private function buildSingleProductOptionValue(ProductVariationModel $variation, &$productOption)
    {
        $productOption['product_option_value']['value'] = "";
        // Anstatt produt_option_values nur value
        /*$variation->getValues()[0]->getI18ns()[0]->get
        [1 => [
            'product_option_id' => '',
            'option_id' => '57',
            'required' => '1',
            'value' => ''
        ]
        ];*/
    }
}
