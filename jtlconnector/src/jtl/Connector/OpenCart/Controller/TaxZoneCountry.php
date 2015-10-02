<?php

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\OpenCart\Utility\SQLs;

class TaxZoneCountry extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf(SQLs::TAX_ZONE_COUNTRY_PULL, $data['geo_zone_id']);
    }
}