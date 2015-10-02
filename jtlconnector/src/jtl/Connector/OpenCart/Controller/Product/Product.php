<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Utility\SQLs;
use jtl\Connector\OpenCart\Utility\TopProduct;

class Product extends MainEntityController
{
    /**
     * @var TopProduct
     */
    private $topProductUtil;

    public function __construct()
    {
        parent::__construct();
        $this->topProductUtil = TopProduct::getInstance();
    }


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
            $this->topProductUtil->handleTopProduct($data->getId()->getEndpoint());
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
}