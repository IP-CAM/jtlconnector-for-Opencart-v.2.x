<?php


namespace jtl\Connector\OpenCart\Controller;


use jtl\Connector\Core\Logger\Logger;
use jtl\Connector\Core\Model\QueryFilter;
use jtl\Connector\Core\Rpc\Error;
use jtl\Connector\Formatter\ExceptionFormatter;
use jtl\Connector\Model\Statistic;
use jtl\Connector\Result\Action;

abstract class MainEntityController extends BaseController
{
    public function statistic(QueryFilter $query)
    {
        $action = new Action();
        $action->setHandled(true);
        try {
            $statModel = new Statistic();
            $statModel->setAvailable(intval($this->getStats()));
            $statModel->setControllerName(lcfirst($this->controllerName));
            $action->setResult($statModel);
        } catch (\Exception $exc) {
            Logger::write(ExceptionFormatter::format($exc), Logger::WARNING, 'controller');
            $err = new Error();
            $err->setCode($exc->getCode());
            $err->setMessage($exc->getMessage());
            $action->setError($err);
        }
        return $action;
    }

    protected abstract function getStats();
}