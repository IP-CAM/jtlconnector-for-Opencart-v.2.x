<?php

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\OpenCart\Utility\SQLs;

class TaxZoneCountry extends BaseController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::taxZoneCountryPull($data['geo_zone_id']);
    }
}