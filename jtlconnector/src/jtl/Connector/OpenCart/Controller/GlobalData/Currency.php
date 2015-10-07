<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\GlobalData
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;

class Currency extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::currencyPull();
    }

    protected function pushData($data, $model)
    {
        $endpointId = $data->getId()->getEndpoint();
        $ocCurrency = $this->oc->loadAdminModel('localisation/currency');
        if (is_null($endpointId)) {
            $currency = $this->mapper->toEndpoint($data);
            $ocCurrency->addCurrency($currency);
        } else {
            $ocCurrency->refresh();
        }
        if ($data->getIsDefault()) {
            $ocSetting = $this->oc->loadAdminModel('setting/setting');
            $ocSetting->editSettingValue('config', 'config_currency', strtoupper($data->getIso()));
        }
    }
}