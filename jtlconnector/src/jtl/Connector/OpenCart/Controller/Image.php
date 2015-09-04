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
        'manufacturerPullQuery' => ImageRelationType::TYPE_MANUFACTURER
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
            foreach ($result as $picture) {
                $model = $this->mapper->toHost($picture);
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
            SELECT pi.image, pi.sort_order, CONCAT("p", pi.product_image_id) as id, pi.product_id as foreign_key
            FROM oc_product_image pi
            LEFT JOIN jtl_connector_link l ON l.endpointId = CONCAT("p", pi.product_image_id) AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_IMAGE, $limit
        );
    }

    private function categoryPullQuery($limit)
    {
        return sprintf('
            SELECT c.image, c.sort_order, CONCAT("c", c.category_id) as id, c.category_id as foreign_key
            FROM oc_category c
            LEFT JOIN jtl_connector_link l ON l.endpointId = CONCAT("c", c.category_id) AND l.type = %d
            WHERE l.hostId IS NULL AND c.image IS NOT NULL AND c.image != ""
            LIMIT %d',
            IdentityLinker::TYPE_IMAGE, $limit
        );
    }

    private function manufacturerPullQuery($limit)
    {
        return sprintf('
            SELECT m.image, m.sort_order, CONCAT("m", m.manufacturer_id) as id, m.manufacturer_id as foreign_key
            FROM oc_manufacturer m
            LEFT JOIN jtl_connector_link l ON l.endpointId = CONCAT("m", m.manufacturer_id) AND l.type = %d
            WHERE l.hostId IS NULL AND m.image IS NOT NULL AND m.image != ""
            LIMIT %d',
            IdentityLinker::TYPE_IMAGE, $limit
        );
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new OperationNotPermitedException("Use the queries for the specific types.");
    }

    protected function pushData($data, $model)
    {
        return $this->{'push' . ucfirst($data->getRelationType()) . 'Image'}($data);
    }

    private function pushProductImage($data)
    {

    }

    protected function deleteData($data, $model)
    {
        switch ($data->getRelationType()) {
            case ImageRelationType::TYPE_PRODUCT:
                $where = sprintf('WHERE product_image_id = %s', $data->getId()->getEndpoint());
                $this->database->query(sprintf('
                    DELETE FROM oc_product_image %s',
                    $where
                ));
                // TODO: get image path
                $image = null;
                $this->database->query(sprintf('
                    UPDATE oc_product
                    SET image = NULL
                    WHERE image = "%s"',
                    $image
                ));
                break;
            case ImageRelationType::TYPE_CATEGORY:
                $this->database->update($data, 'oc_category', 'image', null);
                break;
            case ImageRelationType::TYPE_MANUFACTURER:
                $this->database->update($data, 'oc_manufacturer', 'image', null);
                break;
        }
        $this->database->query(sprintf(
            'DELETE FROM jtl_connector_link WHERE hostId = %d && type = %d',
            $data->getId()->getHost(), IdentityLinker::TYPE_IMAGE
        ));
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
