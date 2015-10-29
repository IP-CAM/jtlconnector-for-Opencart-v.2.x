<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Core\Logger\Logger;
use jtl\Connector\Drawing\ImageRelationType;
use jtl\Connector\Model\Image as ImageModel;
use jtl\Connector\OpenCart\Exceptions\MethodNotAllowedException;
use jtl\Connector\OpenCart\Utility\Constants;
use jtl\Connector\OpenCart\Utility\SQLs;

class Image extends MainEntityController
{
    private $methods = [
        'imageProductCoverPull' => ImageRelationType::TYPE_PRODUCT,
        'imageProductsPull' => ImageRelationType::TYPE_PRODUCT,
        'imageCategoryPull' => ImageRelationType::TYPE_CATEGORY,
        'imageManufacturerPull' => ImageRelationType::TYPE_MANUFACTURER,
        'imageProductVariationValuePull' => ImageRelationType::TYPE_PRODUCT_VARIATION_VALUE
    ];

    /**
     * Add, as long as the limit is not exceeded, images to the result by calling the abstract method for all the
     * different image relation types.
     * @param array $data
     * @param object $model
     * @param null $limit
     * @return array
     */
    public function pullData(array $data, $model, $limit = null)
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
            $sqlMethod = Constants::UTILITY_NAMESPACE . 'SQLs::' . $method;
            $query = call_user_func($sqlMethod, $limit);
            $result = $this->database->query($query);
            foreach ($result as $picture) {
                $model = $this->mapImageToHost($picture, $type);
                $return[] = $model;
                $limit--;
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
        if ($model instanceof ImageModel) {
            $model->setRelationType($type);
            $model->setRemoteURL(HTTPS_CATALOG . 'image/' . $model->getFilename());
        }
        return $model;
    }

    protected function pullQuery(array $data, $limit = null)
    {
        throw new MethodNotAllowedException("Use the queries for the specific types.");
    }

    protected function pushData(ImageModel $data, $model)
    {
        $foreignKey = $data->getForeignKey()->getEndpoint();
        if (!empty($foreignKey)) {
            $this->deleteData($data);
            $path = $this->saveImage($data);
            if ($path !== false) {
                if ($data->getRelationType() === ImageRelationType::TYPE_PRODUCT) {
                    $this->pushProductImage($foreignKey, $path, $data);
                } else {
                    $id = $this->{'push' . ucfirst($data->getRelationType()) . 'Image'}($foreignKey, $path);
                    $data->getId()->setEndpoint($id);
                }
            }
        }
        return $data;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function pushProductImage($foreignKey, $path, ImageModel $data)
    {
        $isCover = $data->getSort() === 1 ? true : false;
        if ($path !== false) {
            if ($isCover) {
                $this->database->query(SQLs::productSetCover($path, $foreignKey));
                $data->getId()->setEndpoint('p_' . $foreignKey);
            } else {
                $query = SQLs::productAddImage($foreignKey, $path, $data->getSort());
                $result = $this->database->query($query);
                $data->getId()->setEndpoint(sprintf('p_%d_%d', $foreignKey, $result));
            }
        }
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function pushCategoryImage($foreignKey, $path)
    {
        $this->database->query(SQLs::imageCategoryPush($path, $foreignKey));
        return 'c_' . $foreignKey;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function pushManufacturerImage($foreignKey, $path)
    {
        $this->database->query(SQLs::imageManufacturerPush($path, $foreignKey));
        return 'm_' . $foreignKey;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function pushProductVariationValueImage($foreignKey, $path)
    {
        $this->database->query(SQLs::imageProductVariationValuePush($path, $foreignKey));
        return 'pvv_' . $foreignKey;
    }

    private function saveImage(ImageModel $data)
    {
        $path = $data->getFilename();
        $filename = $this->buildImageFilename($path);
        $imagePath = "catalog/" . $filename;
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

    protected function deleteData(ImageModel $data)
    {
        $path = $data->getFilename();
        $filename = $this->buildImageFilename($path);
        $id = $data->getForeignKey()->getEndpoint();
        switch ($data->getRelationType()) {
            case ImageRelationType::TYPE_PRODUCT:
                $isCover = $data->getSort() == 1 ? true : false;
                if ($isCover) {
                    $this->database->query(SQLs::productSetCover('', $id));
                } else {
                    $this->database->query(SQLs::imageProductDelete($data->getId()->getEndpoint()));
                }
                break;
            case ImageRelationType::TYPE_CATEGORY:
                $this->database->query(SQLs::imageCategoryPush('', $id));
                break;
            case ImageRelationType::TYPE_MANUFACTURER:
                $this->database->query(SQLs::imageManufacturerPush('', $id));
                break;
            case ImageRelationType::TYPE_PRODUCT_VARIATION_VALUE:
                $this->database->query(SQLs::imageProductVariationValuePush('', $id));
                break;
        }
        $absoluteImagePath = DIR_IMAGE . "catalog/" . $filename;
        if (!is_dir($absoluteImagePath) && file_exists($absoluteImagePath)) {
            unlink($absoluteImagePath);
        }
        return $data;
    }

    private function buildImageFilename($path)
    {
        return basename(html_entity_decode($path, ENT_QUOTES, 'UTF-8'));
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
