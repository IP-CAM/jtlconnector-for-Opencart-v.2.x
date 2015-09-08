<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\Utils;

class ProductAttrI18n extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data);
        $result = $this->database->query($query);
        foreach ($result as $row) {
            $row['value'] = $data['text'];
            $model = $this->mapper->toHost($row);
            $return[] = $model;
        }
        return $return;
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT ad.name, l.code, ad.attribute_id
            FROM oc_attribute_description ad
            LEFT JOIN oc_language l ON ad.language_id = l.language_id
            WHERE ad.attribute_id = %d',
            $data['attribute_id']
        );
    }

    public function pushData($data)
    {
        foreach ($data->getI18ns() as $i18n) {
            $languageId = Utils::getInstance()->getLanguageId($i18n->getLanguageISO());
            if ($languageId !== false) {
                $endpoint = $this->mapper->toEndpoint($i18n);
                $model['product_attribute_description'][intval($languageId)] = $endpoint;
            }
        }
    }
}