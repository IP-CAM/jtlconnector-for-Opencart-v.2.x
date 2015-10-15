<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\Model\MeasurementUnit as MeasurementUnitModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
use jtl\Connector\OpenCart\Utility\SQLs;

class MeasurementUnit extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        $return = [];
        $lengths = $this->database->query(SQLs::measurementUnitLengthsPull());
        $weights = $this->database->query(SQLs::measurementUnitWeightsPull());
        $result = array_merge($lengths, $weights);
        foreach ($result as $row) {
            $host = $this->mapper->toHost($row);
            $return[] = $host;
        }
        return $return;
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new MethodNotAllowedException();
    }

    protected function deleteData(MeasurementUnitModel $data)
    {
        $id = $this->database->queryOne(SQLs::measurementUnitLengthId($data->getDisplayCode()));
        if (!is_null($id)) {
            $ocLength = $this->oc->loadAdminModel('localisation/length_class');
            if ($ocLength instanceof \ModelLocalisationLengthClass) {
                $ocLength->deleteLengthClass($id);
            }
            return $data;
        }
        $id = $this->database->queryOne(SQLs::measurementUnitWeightId($data->getDisplayCode()));
        if (!is_null($id)) {
            $ocWeight = $this->oc->loadAdminModel('localisation/weight_class');
            if ($ocWeight instanceof \ModelLocalisationWeightClass) {
                $ocWeight->deleteWeightClass($id);
            }
            return $data;
        }
        return $data;
    }
}
