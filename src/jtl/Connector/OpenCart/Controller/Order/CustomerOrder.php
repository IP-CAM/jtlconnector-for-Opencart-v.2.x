<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller\Order
 */

namespace jtl\Connector\OpenCart\Controller\Order;

use jtl\Connector\Core\Model\DataModel;
use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class CustomerOrder extends MainEntityController
{
    public function pullData(DataModel $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model, $limit);
    }

    protected function pullQuery(DataModel $data, $limit = null)
    {
        return sprintf('
            SELECT o.*, l.code, c.iso_code_3
            FROM oc_order o
            LEFT JOIN oc_language l ON o.language_id = l.language_id
            LEFT JOIN oc_country c ON o.payment_country_id = c.country_id
            LEFT JOIN jtl_connector_link cl ON o.order_id = cl.endpointId AND cl.type = %d
            WHERE cl.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_CUSTOMER_ORDER, $limit
        );
    }

    protected function getStats()
    {
        return $this->database->queryOne(sprintf('
			SELECT COUNT(*)
			FROM oc_order o
			LEFT JOIN jtl_connector_link l ON o.order_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_CUSTOMER_ORDER
        ));
    }

    protected function pushData(DataModel $data, $model)
    {
        throw new OperationNotPermitedException("Orders should after pulling be handled in the Wawi.");
    }

    protected function deleteData(DataModel $data, $model)
    {
        throw new OperationNotPermitedException("Orders should after pulling be handled in the Wawi.");
    }
}
