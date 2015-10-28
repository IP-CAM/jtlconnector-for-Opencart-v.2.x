<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Core\Logger\Logger;
use jtl\Connector\Model\CrossSelling as CrossSellingModel;
use jtl\Connector\OpenCart\Utility\SQLs;

class CrossSelling extends MainEntityController
{
    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::crossSellingPull($limit);
    }

    protected function pushData(CrossSellingModel $data, $model)
    {
        $this->deleteData($data);
        $id = $data->getProductId()->getEndpoint();
        if (!empty($id)) {
            foreach ($data->getItems() as $item) {
                foreach ($item->getProductIds() as $relatedId) {
                    $this->database->query(SQLs::crossSellingPush($id, $relatedId->getEndpoint()));
                }
            }
            // TODO: ab 1.0.5
            //$data->getId()->setEndpoint($id);
        }
        return $data;
    }

    protected function deleteData(CrossSellingModel $data)
    {
        $id = $data->getProductId()->getEndpoint();
        if (!empty($id)) {
            $this->database->query(SQLs::crossSellingDelete($id));
        }
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::crossSellingStats());
    }
}
