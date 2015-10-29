<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Model\FileUpload as FileUploadModel;
use jtl\Connector\OpenCart\Utility\OpenCart;
use jtl\Connector\OpenCart\Utility\Option as OptionHelper;
use jtl\Connector\OpenCart\Utility\SQLs;

class FileUpload extends BaseController
{
    private $ocOption;
    private $optionHelper;

    public function __construct()
    {
        parent::__construct();
        $this->optionHelper = OptionHelper::getInstance();
        $this->ocOption = OpenCart::getInstance()->loadAdminModel('catalog/option');
    }

    public function pullData(array $data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery(array $data, $limit = null)
    {
        return SQLs::fileUploadPull($limit);
    }

    public function pushData(FileUploadModel $data, $model)
    {
        $option = ['type' => 'file', 'sort_order' => null];
        list($id, $descriptions) = $this->optionHelper->buildOptionDescriptions($data, 'file');
        $option['option_description'] = $descriptions;
        if (is_null($id)) {
            $id = $this->ocOption->addOption($option);
            $query = SQLs::fileUploadPush($data->getProductId()->getEndpoint(), $id, $data->getIsRequired());
            $this->database->query($query);
        } else {
            $this->ocOption->editOption($id, $option);
        }
        return $data;
    }

    protected function deleteData(FileUploadModel $data)
    {
        $this->database->query(SQLs::fileUploadDelete($data->getId()->getEndpoint()));
        return $data;
    }
}
