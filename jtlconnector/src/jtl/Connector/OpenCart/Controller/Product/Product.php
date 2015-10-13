<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Utility\Option as OptionHelper;
use jtl\Connector\OpenCart\Utility\SQLs;
use jtl\Connector\OpenCart\Utility\TopProduct;

class Product extends MainEntityController
{
    private $optionHelper;
    private $topProductUtil;

    public function __construct()
    {
        parent::__construct();
        $this->topProductUtil = TopProduct::getInstance();
        $this->optionHelper = OptionHelper::getInstance();
    }

    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::productPull($limit);
    }

    protected function pushData(ProductModel $data, $model)
    {
        $id = $data->getId()->getEndpoint();
        if (empty($id)) {
            $id = $this->database->query(SQLs::productInsert());
            $data->getId()->setEndpoint($id);
        }
        $endpoint = $this->mapper->toEndpoint($data);
        $this->setTaxClass($data, $endpoint);
        $product = $this->oc->loadAdminModel('catalog/product');
        $product->editProduct($id, $endpoint);
        $this->optionHelper->deleteObsoleteOptions($id);
        if ($data->getIsTopProduct()) {
            $this->topProductUtil->handleTopProduct($id);
        }
        return $data;
    }

    private function setTaxClass(ProductModel $data, &$endpoint)
    {
        $taxClassId = $this->database->queryOne(SQLs::taxClassId($data->getVat()));
        $endpoint['tax_class_id'] = $taxClassId;
    }

    protected function deleteData(ProductModel $data)
    {
        $product = $this->oc->loadAdminModel('catalog/product');
        $product->deleteProduct($data->getId()->getEndpoint());
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::productStats());
    }
}