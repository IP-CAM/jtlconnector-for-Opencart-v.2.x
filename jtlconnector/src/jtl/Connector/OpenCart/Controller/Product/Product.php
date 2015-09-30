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

    protected function pushData($data, $model)
    {
        if (empty($data->getId()->getEndpoint())) {
            $id = $this->database->query('INSERT INTO oc_product () VALUES ()');
            $data->getId()->setEndpoint($id);
        }
        $endpoint = $this->mapper->toEndpoint($data);
        $taxClassId = $this->database->queryOne(sprintf(SQLs::TAX_CLASS_BY_RATE, $data->getVat()));
        $endpoint['tax_class_id'] = $taxClassId;
        $product = $this->oc->loadAdminModel('catalog/product');
        $product->editProduct($data->getId()->getEndpoint(), $endpoint);
        if ($data->getIsTopProduct()) {
            $this->handleTopProduct($data->getId()->getEndpoint());
        }
        return $data;
    }

    protected function deleteData($data)
    {
        $product = $this->oc->loadAdminModel('catalog/product');
        $product->deleteProduct($data->getId()->getEndpoint());
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(sprintf(SQLs::PRODUCT_STATS, IdentityLinker::TYPE_PRODUCT));
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
        if (is_null($moduleId)) {
            $this->addTopProduct($id, $data);
        } else {
            $this->updateTopProduct($id, $data, $moduleId);
        }

        $this->handleTopProductsAppearInLayout();
    }

    private function addTopProduct($id, $data)
    {
        $data['limit'] = 1;
        $data['product'] = [$id];
        $ocModule = $this->oc->loadAdminModel('extension/module');
        $ocModule->addModule('featured', $data);
    }

    private function updateTopProduct($id, $data, $moduleId)
    {
        $ocModule = $this->oc->loadAdminModel('extension/module');
        $module = $ocModule->getModule($moduleId);
        $data['product'] = array_unique(array_merge((array)$module['product'], [$id]), SORT_NUMERIC);
        $data['limit'] = count($data['product']);
        $ocModule->editModule($moduleId, $data);
    }

    private function handleTopProductsAppearInLayout()
    {
        $layoutModule = [
            'code' => 'featured.wawi',
            'position' => 'content_bottom',
            'sort_order' => 1
        ];
        $ocModule = $this->oc->loadAdminModel('design/layout');
        $layout = $ocModule->getLayout(1);
        if (is_null($layout)) {
            $this->addTopProductToLayout($ocModule, $layoutModule);
        } else {
            $this->editTopProductForLayout($layout, $layoutModule, $ocModule);
        }
    }

    private function addTopProductToLayout($ocModule, $layoutModule)
    {
        $ocModule->addLayout([
            'name' => 'Home',
            'layout_route' => [
                'store_id' => 0,
                'route' => 'common/home'
            ],
            'layout_module' => $layoutModule
        ]);
    }

    private function editTopProductForLayout($layout, $layoutModule, $ocModule)
    {
        $found = false;
        if (isset($layout['layout_module'])) {
            foreach ((array)$layout['layout_module'] as $module) {
                if ($module['code'] === 'featured.wawi') {
                    $found = true;
                }
            }
        }
        if (!$found) {
            $layout['layout_module'][] = $layoutModule;
            $ocModule->editLayout(1, $layout);
        }
    }
}