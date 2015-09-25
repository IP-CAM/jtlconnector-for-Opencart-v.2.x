<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Utility\SQLs;

class CrossSelling extends MainEntityController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf(SQLs::CROSS_SELLING_PULL,
            'CONCAT_WS("_", pr.product_id, pr.related_id)', IdentityLinker::TYPE_CROSSSELLING, $limit
        );
    }

    protected function pushData($data, $model)
    {
        $this->deleteData($data);
        $id = $data->getProductId()->getEndpoint();
        if (!empty($id)) {
            foreach ($data->getItems() as $item) {
                foreach ($item->getProductIds() as $relatedId) {
                    $this->database->query(sprintf(SQLs::CROSSELLING_ADD, $id, $relatedId->getEndpoint()));
                }
            }
        }
        return $data;
    }

    protected function deleteData($data)
    {
        $id = $data->getProductId()->getEndpoint();
        if (!empty($id)) {
            $this->database->query(sprintf(SQLs::CROSS_SELLING_DELETE, $id));
        }
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(sprintf(SQLs::CROSS_SELLING_STATS,
            'CONCAT_WS("_", pr.product_id, pr.related_id)', IdentityLinker::TYPE_CROSSSELLING
        ));
    }
}
