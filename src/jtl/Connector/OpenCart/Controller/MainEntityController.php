<?php

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Core\Model\DataModel;

abstract class MainEntityController extends BaseController
{
    protected abstract function pushData(DataModel $data, $model);

    protected abstract function deleteData(DataModel $data, $model);

    protected abstract function getStats();
}