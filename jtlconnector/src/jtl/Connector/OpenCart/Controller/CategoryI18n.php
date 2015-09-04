<?php
namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\OpenCart\Utility\Utils;

class CategoryI18n extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT c.*, l.code
            FROM oc_category_description c
            LEFT JOIN oc_language l ON c.language_id = l.language_id
            WHERE c.category_id = %d',
            $data['category_id']
        );
    }

    public function pushData($data, &$model)
    {
        foreach ($data->getI18ns() as $i18n) {
            $languageId = Utils::getInstance()->getLanguageId($i18n->getLanguageISO());
            if ($languageId !== false) {
                $endpoint = $this->mapper->toEndpoint($i18n);
                $model['category_description'][intval($languageId)] = $endpoint;
            }
        }
    }
}