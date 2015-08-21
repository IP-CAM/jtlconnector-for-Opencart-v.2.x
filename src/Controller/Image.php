<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Drawing\ImageRelationType;
use jtl\Connector\Linker\IdentityLinker;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class Image extends MainEntityController
{
    private $methods = [
        'productPullQuery' => ImageRelationType::TYPE_PRODUCT,
        'categoryPullQuery' => ImageRelationType::TYPE_CATEGORY,
        'manufacturerPullQuery' => ImageRelationType::TYPE_MANUFACTURER,
        'specificValuePullQuery' => ImageRelationType::TYPE_SPECIFIC_VALUE
    ];

    public function pullData($data, $model, $limit = null)
    {
        $return = [];
        reset($this->methods);
        while (count($return) < $limit) {
            if ($this->addNextImages($this->methods, $return, $limit) === false) {
                break;
            }
        }
        return $return;
    }

    private function addNextImages(&$methods, &$return, &$limit)
    {
        list($method, $type) = each($methods);
        if (!is_null($method)) {
            $query = $this->{$method}($limit);
            $result = $this->database->query($query);
            foreach ($result as $pi) {
                $model = $this->mapper->toHost($pi);
                $model->setRelationType($type);
                $model->setRemoteURL(HTTP_SERVER . 'image/' . $model->getFilename());
                $return[] = $model;
            }
            return true;
        } else {
            return false;
        }
    }

    private function productPullQuery($limit)
    {
        return sprintf('
            SELECT pi.image, pi.sort_order, pi.product_image_id as id, pi.product_id as foreign_key
            FROM oc_product_image pi
            LEFT JOIN jtl_connector_link l ON l.endpointId = pi.product_image_id AND l.relation_type = %d AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_PRODUCT, IdentityLinker::TYPE_IMAGE, $limit
        );
    }

    private function categoryPullQuery($limit)
    {
        return sprintf('
            SELECT c.image, c.sort_order, c.category_id as id, c.category_id as foreign_key
            FROM oc_category c
            LEFT JOIN jtl_connector_link l ON l.endpointId = c.category_id AND l.relation_type = %d AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_CATEGORY, IdentityLinker::TYPE_IMAGE, $limit
        );
    }

    private function manufacturerPullQuery($limit)
    {
        return sprintf('
            SELECT m.image, m.sort_order, m.manufacturer_id as id, m.manufacturer_id as foreign_key
            FROM oc_manufacturer m
            LEFT JOIN jtl_connector_link l ON l.endpointId = m.manufacturer_id AND l.relation_type = %d AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_MANUFACTURER, IdentityLinker::TYPE_IMAGE, $limit
        );
    }

    private function specificValuePullQuery($limit)
    {
        return sprintf('
            SELECT v.image, v.sort_order, v.option_value_id as id, c.option_value_id as foreign_key
            FROM oc_option_value v
            LEFT JOIN jtl_connector_link l ON l.endpointId = v.option_value_id AND l.relation_type = %d AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_SPECIFIC_VALUE, IdentityLinker::TYPE_IMAGE, $limit
        );
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new OperationNotPermitedException("Use the queries for the specific types.");
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
        $return = [];
        $limit = PHP_INT_MAX;
        reset($this->methods);
        while ($this->addNextImages($this->methods, $return, $limit) === true) {
        }
        return count($return);
    }
}
