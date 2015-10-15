<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Model\DeliveryNote as DeliveryNoteModel;
use jtl\Connector\Model\DeliveryNoteTrackingList as DelivryNoteTrackingListModel;
use jtl\Connector\OpenCart\Utility\SQLs;

class DeliveryNote extends MainEntityController
{
    public function pullData(array $data, $model, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data, $limit);
        $result = $this->database->query($query);
        foreach ($result as $row) {
            $host = $this->mapper->toHost($row);
            if ($host instanceof DeliveryNoteModel) {
                $trackingList = new DelivryNoteTrackingListModel();
                $trackingList->setName($row['shipping_method']);
                $trackingList->addCode($row['tracking']);
                $host->addTrackingList($trackingList);
            }
            $return[] = $host;
        }
        return $return;
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::deliveryNotePull($limit);
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::deliveryNoteStats());
    }
}