<?php


namespace jtl\Connector\OpenCart\Controller;


abstract class MainEntityController extends BaseController
{
    protected abstract function pushData($data, $model);

    protected abstract function deleteData($data);

    protected abstract function getStats();
}