<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Linker\ChecksumLinker;
use jtl\Connector\Model\Checksum;
use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\Model\ProductVariation as ProductVariationModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Mapper\Product\ProductVariationValue as ProductVariationValueMapper;
use jtl\Connector\OpenCart\Utility\OptionHelper;
use jtl\Connector\OpenCart\Utility\SQLs;

class ProductVariation extends BaseController
{
    private $optionHelper;

    public function __construct()
    {
        parent::__construct();
        $this->optionHelper = OptionHelper::getInstance();
    }


    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        // Do not pull checkbox as configuration items are not supported yet.
        // Do not pull file as uploads are handled extra.
        return SQLs::productVariationPull($data['product_id']);
    }

    public function pushData(ProductModel $data, &$model)
    {
        $model['product_option'] = [];
        if (count($data->getVariations()) > 0) {
            $checksum = ChecksumLinker::find($data, Checksum::TYPE_VARIATION);
            if ($checksum === null || $checksum->hasChanged() === true) {
                foreach ($data->getVariations() as $variation) {
                    $option = $this->mapper->toEndpoint($variation);
                    list($id, $descriptions) = $this->optionHelper->buildOptionDescriptions($variation);
                    $option['option_description'] = $descriptions;
                    $option['option_value'] = $this->optionHelper->buildOptionValues($variation, $id);
                    $ocOption = $this->oc->loadAdminModel('catalog/option');
                    if (is_null($id)) {
                        $id = $ocOption->addOption($option);
                    } else {
                        $ocOption->editOption($id, $option);
                    }
                    $productOption = $this->mapper->toEndpoint($variation);
                    $productOption['option_id'] = $id;
                    $this->buildProductOptionValues($variation, $productOption);
                    $model['product_option'][] = $productOption;
                }
            }
        }
    }

    private function buildProductOptionValues(ProductVariationModel $variation, &$productOption)
    {
        $singleTypes = [ProductVariationModel::TYPE_FREE_TEXT, ProductVariationModel::TYPE_FREE_TEXT_OBLIGATORY];
        if (!in_array($variation->getType(), $singleTypes)) {
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
                $optionValueId = $this->optionHelper->findExistingOptionValue($i18n, $productOption['option_id']);
                if (!is_null($optionValueId)) {
                    break;
                }
            }
            $productOptionValue['option_value_id'] = $optionValueId;
            $productOption['product_option_value'][] = $productOptionValue;
        }
    }
}
