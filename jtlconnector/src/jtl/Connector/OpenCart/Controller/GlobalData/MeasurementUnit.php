<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
use jtl\Connector\OpenCart\Utility\SQLs;

class MeasurementUnit extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        $return = [];
        $lengths = $this->database->query(SQLs::MEASUREMENT_UNIT_PULL_LENGTHS);
        $weights = $this->database->query(SQLs::MEASUREMENT_UNIT_PULL_WEIGHTS);
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

    protected function deleteData($data)
    {
        $id = $this->database->queryOne(sprintf(SQLs::MEASUREMENT_UNIT_FIND_LENGTH, $data->getDisplayCode()));
        if (!is_null($id)) {
            $length = $this->oc->loadAdminModel('localisation/length_class');
            $length->deleteLengthClass($id);
            return $data;
        }
        $id = $this->database->queryOne(sprintf(SQLs::MEASUREMENT_UNIT_FIND_WEIGHT, $data->getDisplayCode()));
        if (!is_null($id)) {
            $weight = $this->oc->loadAdminModel('localisation/weight_class');
            $weight->deleteWeigthClass($id);
            return $data;
        }
        return $data;
    }
}
