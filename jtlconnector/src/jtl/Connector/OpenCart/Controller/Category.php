<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Model\Category as CategoryModel;
use jtl\Connector\OpenCart\Utility\SQLs;

class Category extends MainEntityController
{
    private static $idCache = [];

    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery(array $data, $limit = null)
    {
        return SQLs::categoryPull($limit);
    }

    public function pushData(CategoryModel $data, $model)
    {
        if ($data->getParentCategoryId() !== null && isset(self::$idCache[$data->getParentCategoryId()->getHost()])) {
            $data->getParentCategoryId()->setEndpoint(self::$idCache[$data->getParentCategoryId()->getHost()]);
        }
        $category = $this->mapper->toEndpoint($data);
        $ocCategory = $this->oc->loadAdminModel('catalog/category');
        if ($ocCategory instanceof \ModelCatalogCategory) {
            $id = $data->getId()->getEndpoint();
            if (empty($id)) {
                $id = $ocCategory->addCategory($category);
                $data->getId()->setEndpoint($id);
                self::$idCache[$data->getId()->getHost()] = $id;
            } else {
                $ocCategory->editCategory($id, $category);
            }
            SQLs::categoryTreeUpdate($id, $data->getLevel());
        }
        return $data;
    }

    protected function deleteData(CategoryModel $data)
    {
        $ocCategory = $this->oc->loadAdminModel('catalog/category');
        if ($ocCategory instanceof \ModelCatalogCategory) {
            $ocCategory->deleteCategory($data->getId()->getEndpoint());
            SQLs::categoryTreeDelete($data->getId()->getEndpoint());
        }
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::categoryStats());
    }
}
