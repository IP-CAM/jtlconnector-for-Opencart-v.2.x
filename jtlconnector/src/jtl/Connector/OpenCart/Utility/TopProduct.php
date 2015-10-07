<?php

namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Core\Utilities\Singleton;

class TopProduct extends Singleton
{
    const LAYOUT_KEY = 1;
    const LAYOUT_NAME = 'Home';
    const LAYOUT_ROUTE = 'common/home';
    const TOP_PRODUCTS_NAME = 'Featured - Wawi';

    private $oc;
    private $database;

    public function __construct()
    {
        $this->database = Db::getInstance();
        $this->oc = OpenCart::getInstance();
    }

    public function handleTopProduct($id)
    {
        $data = [
            'name' => self::TOP_PRODUCTS_NAME,
            'width' => '200',
            'height' => '200',
            'status' => 1
        ];
        $moduleId = $this->database->queryOne(SQLs::moduleIdTopProducts());
        if (is_null($moduleId)) {
            $this->addTopProduct($id, $data);
            $moduleId = $this->database->queryOne(SQLs::moduleIdTopProducts());
        } else {
            $this->updateTopProduct($id, $data, $moduleId);
        }
        $this->handleTopProductsAppearInLayout($moduleId);
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

    private function handleTopProductsAppearInLayout($moduleId)
    {
        $layoutModule = [
            'code' => 'featured.' . $moduleId,
            'position' => 'content_bottom',
            'sort_order' => 1
        ];
        $ocModule = $this->oc->loadAdminModel('design/layout');
        $layout = $ocModule->getLayout(self::LAYOUT_KEY);
        if (is_null($layout)) {
            $this->addTopProductToLayout($ocModule, $layoutModule);
        } else {
            $this->editTopProductForLayout($layout, $layoutModule, $ocModule);
        }
    }

    private function addTopProductToLayout($ocModule, $layoutModule)
    {
        $ocModule->addLayout([
            'name' => self::LAYOUT_NAME,
            'layout_route' => [
                'store_id' => 0,
                'route' => self::LAYOUT_ROUTE
            ],
            'layout_module' => $layoutModule
        ]);
    }

    private function editTopProductForLayout($layout, $layoutModule, $ocModule)
    {
        $found = false;
        $modules = $ocModule->getLayoutModules(self::LAYOUT_KEY);
        foreach ($modules as $module) {
            if ($module['code'] === $layoutModule['code']) {
                $found = true;
            }
        }
        if (!$found) {
            $modules[] = $layoutModule;
            $layout['layout_module'] = $modules;
            $layout['layout_route'] = $ocModule->getLayoutRoutes(self::LAYOUT_KEY);
            $ocModule->editLayout(self::LAYOUT_KEY, $layout);
        }
    }
}