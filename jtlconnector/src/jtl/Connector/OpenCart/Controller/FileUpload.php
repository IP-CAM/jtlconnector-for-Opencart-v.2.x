<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Utility\OpenCart;
use jtl\Connector\OpenCart\Utility\OptionHelper;
use jtl\Connector\OpenCart\Utility\SQLs;

class FileUpload extends BaseController
{
    private $ocOption;
    /**
     * @var OptionHelper
     */
    private $optionHelper;

    public function __construct()
    {
        parent::__construct();
        $this->optionHelper = OptionHelper::getInstance();
        $this->ocOption = OpenCart::getInstance()->loadAdminModel('catalog/option');
    }

    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf(SQLs::FILE_UPLOAD_PULL, $limit);
    }

    public function pushData($data, $model)
    {
        $option = ['type' => 'file', 'sort_order' => null];
        $optionId = $this->optionHelper->buildOptionDescriptions($data, $option);
        if (is_null($optionId)) {
            $optionId = $this->ocOption->addOption($option);
        } else {
            $this->ocOption->editOption($optionId, $option);
        }
        $query = sprintf(SQLs::FILE_UPLOAD_PUSH, $data->getProductId()->getHost(), $optionId, $data->getIsRequired());
        $this->database->query($query);
        return $data;
    }

    protected function deleteData($data)
    {
        $this->database->query(sprintf(SQLs::FILE_UPLOAD_DELETE, $data->getId()->getEndpoint()));
        return $data;
    }
}
