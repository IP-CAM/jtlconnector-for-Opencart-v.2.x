<?php
namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\Model\ProductVariation as ProductVariationModel;
use jtl\Connector\OpenCart\Controller\BaseController;
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
            $endpoint = $this->mapper->toEndpoint($variation);
            $optionId = $this->getOrCreateOption($variation, $endpoint);
            $endpoint['option_id'] = $optionId;
            $model['product_option'][] = $endpoint;
        }
    }

    /**
     * Check if there is already an option existing based on its descriptions.
     */
    private function getOrCreateOption(ProductVariationModel $variation, array &$endpoint)
    {
        $optionId = null;
        foreach ($variation->getI18ns() as $i18n) {
            $optionId = $this->findExistingOption($i18n);
            if (!is_null($optionId)) {
                break;
            }
        }
        $model = $this->buildOption($variation, $endpoint);
        $option = $this->oc->loadModel('catalog/option');

        // TODO: do it like with the product and create it and then call update as
        if (!is_null($optionId)) {
            $option->editOption($optionId, $model);
            return $optionId;
        }
        $optionId = $option->addOption($model);
        return $optionId;
    }

    private function findExistingOption($i18n)
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

    private function buildOption(ProductVariationModel $variation, array &$endpoint)
    {
        $model = [
            'sort_order' => $variation->getSort(),
            'type' => count($variation->getValues()) > 1 ? 'select' : 'text',
            'option_description' => $this->utils->array_remove($endpoint, 'option_description')
        ];
        foreach ($endpoint['product_option_value'] as $pov) {
            $optionValueDescriptions = $this->utils->array_remove($pov, 'option_value');
            $optionValues = [
                'image' => null,
                'sort_order' => 0,
                'option_value_id' => $pov['option_value_id'],
                'option_value_description' => $optionValueDescriptions
            ];
            $model['option_value'][] = $optionValues;
        }
        return $model;
    }
}
