<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\Specific;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\Model\Specific as SpecificModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;
use jtl\Connector\OpenCart\Utility\Utils;

class SpecificValue extends BaseController
{
    /**
     * @var Utils
     */
    private $utils;

    public function __construct()
    {
        parent::__construct();
        $this->utils = Utils::getInstance();
    }

    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf(SQLs::SPECIFIC_VALUE_PULL, IdentityLinker::TYPE_SPECIFIC_VALUE, $data['filter_group_id']);
    }

    public function pushData(SpecificModel $data, &$model)
    {
        foreach ((array)$data->getValues() as $value) {
            if (is_null($value->getId()->getEndpoint())) {
                $query = sprintf(SQLs::SPECIFIC_VALUE_PUSH, $data->getId()->getEndpoint(), $value->getSort());
                $id = $this->database->query($query);
                $value->getId()->setEndpoint($id);
            } else {
                $query = sprintf(SQLs::SPECIFIC_VALUE_UPDATE, $value->getSort(), $data->getId()->getEndpoint());
                $this->database->query($query);
            }
            foreach ($value->getI18ns() as $i18n) {
                $languageId = $this->utils->getLanguageId($i18n->getLanguageISO());
                $query = sprintf(SQLs::SPECIFIC_VALUE_I18N_PUSH, $value->getId()->getEndpoint(), $languageId,
                    $data->getId()->getEndpoint(), $i18n->getValue());
                $this->database->query($query);
            }
        }
    }
}