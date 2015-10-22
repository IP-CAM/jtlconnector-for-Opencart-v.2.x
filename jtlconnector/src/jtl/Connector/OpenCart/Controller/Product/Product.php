<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
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

    public function pullData(array $data, $model, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data, $limit);
        $result = $this->database->query($query);
        foreach ($result as $row) {
            $host = $this->mapper->toHost($row);
            if ($host instanceof ProductModel) {
                $vat = $this->database->queryOne(SQLs::taxRate($row['tax_class_id']));
                if (is_string($vat)) {
                    $host->setVat(doubleval($vat));
                }
                $isTopProduct = $this->topProductUtil->isTopProduct($row['product_id']);
                $host->setIsTopProduct($isTopProduct);
            }
            $return[] = $host;
        }
        return $return;
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
        $ocProduct = $this->oc->loadAdminModel('catalog/product');
        if ($ocProduct instanceof \ModelCatalogProduct) {
            $ocProduct->editProduct($id, $endpoint);
        }
        if ($data->getIsTopProduct()) {
            $this->topProductUtil->handleTopProductPush($id);
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
        $ocProduct = $this->oc->loadAdminModel('catalog/product');
        if ($ocProduct instanceof \ModelCatalogProduct) {
            $ocProduct->deleteProduct($data->getId()->getEndpoint());
        }
        return $data;
    }

    protected function getStats()
    {
        return $this->database->queryOne(SQLs::productStats());
    }
}