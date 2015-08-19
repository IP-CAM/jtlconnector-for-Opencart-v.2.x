<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;

class Customer extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data, $limit);
        $result = $this->db->query($query);
        foreach ($result as $row) {
            $model = $this->mapper->toHost($row);
            $return[] = $model;
        }
        return $return;
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT c.*, a.company, a.address_1, a.city, a.postcode, a.country_id, co.iso_code_3, co.name
            FROM oc_customer c
            LEFT JOIN oc_address a ON c.address_id = a.address_id
            LEFT JOIN oc_country co ON a.country_id = co.country_id
            LEFT JOIN jtl_connector_link l ON c.customer_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_CUSTOMER, $limit
        );
    }

    protected function pushData($data, $model)
    {
        // TODO: Implement pushData() method.
    }

    protected function deleteData($data, $model)
    {
        // TODO: Implement deleteData() method.
    }

    protected function getStats()
    {
        return $this->db->query(sprintf('
			SELECT COUNT(*)
			FROM oc_customer c
			LEFT JOIN jtl_connector_link l ON c.customer_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_CUSTOMER
        ));
    }


}
