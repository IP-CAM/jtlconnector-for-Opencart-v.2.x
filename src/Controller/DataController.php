<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Core\Controller\Controller;
use jtl\Connector\Core\Logger\Logger;
use jtl\Connector\Core\Model\DataModel;
use jtl\Connector\Core\Model\QueryFilter;
use jtl\Connector\Core\Rpc\Error;
use jtl\Connector\Formatter\ExceptionFormatter;
use jtl\Connector\Model\Statistic;
use jtl\Connector\OpenCart\Utility\Db;
use jtl\Connector\Result\Action;

abstract class DataController extends Controller
{
    protected $db = null;
    protected $mapper = null;
    private $controllerName = null;

    public function __construct()
    {
        $this->db = Db::getInstance();
        $reflect = new \ReflectionClass($this);
        $this->controllerName = $reflect->getShortName();
        $mapperClass = "\\jtl\\Connector\\OpenCart\\Mapper\\{$reflect->getShortName()}";
        if (class_exists($mapperClass)) {
            $this->mapper = new $mapperClass();
        }
    }

    public function pull(QueryFilter $query)
    {
        $action = new Action();
        $action->setHandled(true);
        try {
            $result = $this->pullData(null, null, $query->getLimit());
            $action->setResult($result);
        } catch (\Exception $exc) {
            Logger::write(ExceptionFormatter::format($exc), Logger::WARNING, 'controller');
            $err = new Error();
            $err->setCode($exc->getCode());
            $err->setMessage($exc->getFile() . ' (' . $exc->getLine() . '):' . $exc->getMessage());
            $action->setError($err);
        }
        return $action;
    }

    public function push(DataModel $data)
    {
        $action = new Action();
        $action->setHandled(true);
        try {
            if (method_exists($this, 'prePush')) {
                $this->prePush($data);
            }
            $result = $this->pushData($data, null);
            if (method_exists($this, 'postPush')) {
                $this->postPush($data, $result);
            }
            $action->setResult($result);
        } catch (\Exception $exc) {
            Logger::write(ExceptionFormatter::format($exc), Logger::WARNING, 'controller');
            $err = new Error();
            $err->setCode($exc->getCode());
            $err->setMessage($exc->getFile() . ' (' . $exc->getLine() . '):' . $exc->getMessage());
            $action->setError($err);
        }
        return $action;
    }

    public function delete(DataModel $data)
    {
        $action = new Action();
        $action->setHandled(true);
        try {
            $action->setResult($this->deleteData($data, null));
        } catch (\Exception $exc) {
            Logger::write(ExceptionFormatter::format($exc), Logger::WARNING, 'controller');
            $err = new Error();
            $err->setCode($exc->getCode());
            $err->setMessage($exc->getFile() . ' (' . $exc->getLine() . '):' . $exc->getMessage());
            $action->setError($err);
        }
        return $action;
    }

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

    protected abstract function pullQuery($data, $limit = null);
}