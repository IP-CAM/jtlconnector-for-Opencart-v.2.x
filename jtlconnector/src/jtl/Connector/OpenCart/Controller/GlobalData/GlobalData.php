<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\Model\GlobalData as GlobalDataModel;
use jtl\Connector\OpenCart\Controller\GlobalData\Currency as CurrencyController;
use jtl\Connector\OpenCart\Controller\GlobalData\MeasurementUnit as MeasurementUnitController;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
use jtl\Connector\OpenCart\Utility\SQLs;

class GlobalData extends MainEntityController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return [$this->mapper->toHost([])];
    }

    protected function pullQuery(array $data, $limit = null)
    {
        throw new MethodNotAllowedException("Just pull the different global data children.");
    }

    protected function pushData(GlobalDataModel $data, $model)
    {
        $currencyController = new CurrencyController();
        foreach ($data->getCurrencies() as $currency) {
            $currencyController->push($currency);
        }
        return $data;
    }

    protected function deleteData(GlobalDataModel $data)
    {
        $measurementUnitController = new MeasurementUnitController();
        foreach ($data->getMeasurementUnits() as $unit) {
            $measurementUnitController->delete($unit);
        }
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::globalDataStats());
    }
}