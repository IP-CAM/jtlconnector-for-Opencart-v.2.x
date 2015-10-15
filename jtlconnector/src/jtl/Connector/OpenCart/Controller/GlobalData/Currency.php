<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\Model\Currency as CurrencyModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;

class Currency extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::currencyPull();
    }

    protected function pushData(CurrencyModel $data, $model)
    {
        $currencyId = $data->getId()->getEndpoint();
        $ocCurrency = $this->oc->loadAdminModel('localisation/currency');
        if ($ocCurrency instanceof \ModelLocalisationCurrency) {
            if (is_null($currencyId)) {
                $currency = $this->mapper->toEndpoint($data);
                $ocCurrency->addCurrency($currency);
            } else {
                $ocCurrency->refresh();
            }
        }
        if ($data->getIsDefault()) {
            $ocSetting = $this->oc->loadAdminModel('setting/setting');
            if ($ocSetting instanceof \ModelSettingSetting) {
                $ocSetting->editSettingValue('config', 'config_currency', strtoupper($data->getIso()));
            }
        }
    }
}