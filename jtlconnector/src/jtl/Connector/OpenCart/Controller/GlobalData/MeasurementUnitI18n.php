<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\GlobalData;

use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Exceptions\DataAlreadyFetchedException;
use jtl\Connector\OpenCart\Utility\SQLs;

class MeasurementUnitI18n extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        $return = [];
        if (strrpos($data['id'], 'l_') !== false) {
            $query = sprintf(SQLs::MEASUREMENT_UNIT_I18N_PULL_LENGTHS, str_replace('l_', '', $data['id']));
        } else {
            $query = sprintf(SQLs::MEASUREMENT_UNIT_I18N_PULL_WEIGHTS, str_replace('w_', '', $data['id']));
        }
        $result = $this->database->query($query);
        foreach ($result as $row) {
            $host = $this->mapper->toHost($row);
            $return[] = $host;
        }
        return $return;
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new DataAlreadyFetchedException();
    }
}