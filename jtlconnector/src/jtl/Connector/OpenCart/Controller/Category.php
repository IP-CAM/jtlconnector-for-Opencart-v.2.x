<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Utility\SQLs;

class Category extends MainEntityController
{
    private static $idCache = [];
    private $ocCategory;

    public function __construct()
    {
        parent::__construct();
        $this->ocCategory = $this->oc->loadAdminModel('catalog/category');
    }


    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf(SQLs::CATEGORY_PULL, IdentityLinker::TYPE_CATEGORY, $limit);
    }

    public function pushData($data, $model)
    {

        if (isset(self::$idCache[$data->getParentCategoryId()->getHost()])) {
            $data->getParentCategoryId()->setEndpoint(self::$idCache[$data->getParentCategoryId()->getHost()]);
        }
        $category = $this->mapper->toEndpoint($data);
        if (is_null($data->getId()->getEndpoint())) {
            $id = $this->ocCategory->addCategory($category);
            $data->getId()->setEndpoint($id);
            self::$idCache[$data->getId()->getHost()] = $id;
        } else {
            $this->ocCategory->editCategory($data->getId()->getEndpoint(), $category);
        }
        return $data;
    }

    protected function deleteData($data)
    {
        $this->ocCategory->deleteCategory(intval($data->getId()->getEndpoint()));
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::CATEGORY_STATS, IdentityLinker::TYPE_CATEGORY);
    }
}
