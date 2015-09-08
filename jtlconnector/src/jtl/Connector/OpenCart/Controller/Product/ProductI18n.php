<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\Utils;

class ProductI18n extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT p.*, l.code
            FROM oc_product_description p
            LEFT JOIN oc_language l ON p.language_id = l.language_id
            WHERE p.product_id = %d',
            $data['product_id']
        );
    }

    public function pushData($data, &$model)
    {
        foreach ($data->getI18ns() as $i18n) {
            $languageId = Utils::getInstance()->getLanguageId($i18n->getLanguageISO());
            if ($languageId !== false) {
                $endpoint = $this->mapper->toEndpoint($i18n);
                $model['product_description'][intval($languageId)] = $endpoint;
            }
        }
    }
}