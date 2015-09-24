<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Utility\SQLs;

class Product extends MainEntityController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf(SQLs::PRODUCT_PULL, IdentityLinker::TYPE_PRODUCT, $limit);
    }

    /**
     * @param $data \jtl\Connector\Model\Product;
     * @param $model
     * @return mixed
     */
    protected function pushData($data, $model)
    {
        if (empty($data->getId()->getEndpoint())) {
            $id = $this->database->query('INSERT INTO oc_product () VALUES ()');
            $data->getId()->setEndpoint($id);
        }
        $endpoint = $this->mapper->toEndpoint($data);
        $endpoint['tax_class_id'] = $this->database->queryOne(SQLs::TAX_CLASS_BY_RATE, $data->getVat());
        $product = $this->oc->loadAdminModel('catalog/product');
        $product->editProduct($data->getId()->getEndpoint(), $endpoint);
        if ($data->getIsTopProduct()) {
            $this->handleTopProduct($data->getId()->getEndpoin());
        }
        return $data;
    }

    protected function deleteData($data)
    {
        // TODO: Keep in mind that picture files are not deleted automatically.
        $product = $this->oc->loadAdminModel('catalog/product');
        $product->deleteProduct($data->getId()->getEndpoint());
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::PRODUCT_STATS, IdentityLinker::TYPE_PRODUCT);
    }

    private function handleTopProduct($id)
    {
        $data = [
            'name' => 'Featured - Wawi',
            'width' => '200',
            'height' => '200',
            'status' => '1'
        ];
        $moduleId = $this->database->queryOne(SQLs::MODULE_FEATURED_WAWI);
        $ocModule = $this->oc->loadAdminModel('extension/module');
        if (is_null($moduleId)) {
            $data['product'] = [$id];
            $data['limit'] = 1;
            $ocModule->addModule('featured', $data);
        } else {
            $module = $ocModule->getModule($moduleId);
            $data['product'] = array_merge($module['products'], [$id]);
            $data['limit'] = count($data['product']);
            $ocModule->editModule($moduleId, $data);
        }
    }
}
