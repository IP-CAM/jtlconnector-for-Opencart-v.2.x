<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Drawing\ImageRelationType;
use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\Model\Image as ImageModel;
use jtl\Connector\OpenCart\Utility\SQLs;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class Image extends MainEntityController
{
    private $methods = [
        'productPullQuery' => ImageRelationType::TYPE_PRODUCT,
        'categoryPullQuery' => ImageRelationType::TYPE_CATEGORY,
        'manufacturerPullQuery' => ImageRelationType::TYPE_MANUFACTURER
    ];

    /**
     * Add as long as the limit is not exceeded images to the result by calling the abstract method for all the
     * different image relation types.
     */
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

    /**
     * Call for each image relation type the matching pull method and return if there is a type left.
     */
    private function addNextImages(&$methods, &$return, &$limit)
    {
        list($method, $type) = each($methods);
        if (!is_null($method)) {
            $query = $this->{$method}($limit);
            $result = $this->database->query($query);
            foreach ($result as $picture) {
                $model = $this->mapImageToHost($picture, $type);
                $return[] = $model;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Generic mapping of image data to the hosts format.
     */
    private function mapImageToHost($picture, $type)
    {
        $model = $this->mapper->toHost($picture);
        $model->setRelationType($type);
        $model->setRemoteURL(HTTP_CATALOG . 'image/' . $model->getFilename());
        return $model;
    }

    private function productPullQuery($limit)
    {
        return sprintf(SQLs::IMAGE_PRODUCT_PULL, IdentityLinker::TYPE_IMAGE, $limit);
    }

    private function categoryPullQuery($limit)
    {
        return sprintf(SQLs::IMAGE_CATEGORY_PULL, IdentityLinker::TYPE_IMAGE, $limit);
    }

    private function manufacturerPullQuery($limit)
    {
        return sprintf(SQLs::IMAGE_MANUFACTURER_PULL, IdentityLinker::TYPE_IMAGE, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        throw new OperationNotPermitedException("Use the queries for the specific types.");
    }

    protected function pushData($data, $model)
    {
        $id = $data->getForeignKey()->getEndpoint();
        if (!empty($id)) {
            $this->deleteData($data);
            $this->{'push' . ucfirst($data->getRelationType()) . 'Image'}($id, $data);
        }
        return $data;
    }

    private function pushProductImage($id, ImageModel $data)
    {
        $isCover = $data->getSort() == 1 ? true : false;
        $path = $this->saveImage($data);
        if ($path !== false) {
            if ($isCover) {
                $this->database->query(sprintf(SQLs::PRODUCT_SET_COVER, $path, $id));
            } else {
                $this->database->query(sprintf(SQLs::PRODUCT_ADD_IMAGE, $id, $path, $data->getSort()));
            }
        }
    }

    private function pushCategoryImage($id, ImageModel $data)
    {
        $path = $this->saveImage($data);
        if ($path !== false) {
            $this->database->query(sprintf(SQLs::IMAGE_CATEGORY_PUSH, $path, $id));
        }
    }

    private function pushManufacturerImage($id, ImageModel $data)
    {
        $path = $this->saveImage($data);
        if ($path !== false) {
            $this->database->query(sprintf(SQLs::IMAGE_MANUFACTURER_PUSH, $path, $id));
        }
    }

    private function saveImage(ImageModel $data)
    {
        $path = $data->getFilename();
        $filename = $this->buildImageFilename($path);
        $imagePath = $this->buildImagePath($filename, $data->getRelationType());
        $destination = DIR_IMAGE . $imagePath;

        $allowed = ['jpg', 'jpeg', 'gif', 'png'];
        if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
            return false;
        }
        $content = file_get_contents($path);
        if (preg_match('/\<\?php/i', $content)) {
            return false;
        }
        if (copy($path, $destination)) {
            return $imagePath;
        }
        return false;
    }

    protected function deleteData($data)
    {
        $path = $data->getFilename();
        $filename = $this->buildImageFilename($path);
        $imagePath = $this->buildImagePath($filename, $data->getRelationType());
        switch ($data->getRelationType()) {
            case ImageRelationType::TYPE_PRODUCT:
                $isCover = $data->getSort() == 1 ? true : false;
                if ($isCover) {
                    $this->database->query(sprintf(SQLs::PRODUCT_RESET_COVER, $data->getForeignKey()->getEndpoint()));
                } else {
                    $this->database->query(sprintf(SQLs::IMAGE_PRODUCT_DELETE, $data->getId()->getEndpoint()));
                }
                break;
            case ImageRelationType::TYPE_CATEGORY:
                $this->database->update($data, 'oc_category', 'image', null);
                break;
            case ImageRelationType::TYPE_MANUFACTURER:
                $this->database->update($data, 'oc_manufacturer', 'image', null);
                break;
        }
        $absoluteImagePath = DIR_IMAGE . $imagePath;
        if (!is_dir($absoluteImagePath) && file_exists($absoluteImagePath)) {
            unlink($absoluteImagePath);
        }
        return $data;
    }

    /**
     * Build the image filename that can be used in the database.
     *
     * @param $path string The image path.
     * @return string The filename ready for the database.
     */
    private function buildImageFilename($path)
    {
        return basename(html_entity_decode($path, ENT_QUOTES, 'UTF-8'));
    }

    /**
     * Build the path of the image that is stored in the database.
     *
     * @param $filename string  The name of the file including its extension.
     * @param $type     string  The type of the file which is mirrored as folder.
     * @return string The path that is stored in the database.
     */
    private function buildImagePath($filename, $type)
    {
        return "catalog/wawi/{$type}/{$filename}";
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
