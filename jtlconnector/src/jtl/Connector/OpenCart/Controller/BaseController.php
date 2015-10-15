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
use jtl\Connector\OpenCart\Mapper\BaseMapper;
use jtl\Connector\OpenCart\Utility\Db;
use jtl\Connector\OpenCart\Utility\OpenCart;
use jtl\Connector\OpenCart\Utility\Utils;
use jtl\Connector\Result\Action;

abstract class BaseController extends Controller
{
    /**
     * @var BaseMapper
     */
    protected $mapper = null;
    protected $oc = null;
    protected $utils = null;
    protected $database = null;
    protected $controllerName = null;

    public function __construct()
    {
        $this->database = Db::getInstance();
        $this->oc = OpenCart::getInstance();
        $this->utils = Utils::getInstance();

        $reflect = new \ReflectionClass($this);
        $shortName = $reflect->getShortName();
        $this->controllerName = $shortName;
        $mapperClass = str_replace('Controller', 'Mapper', $reflect->getNamespaceName()) . '\\' . $shortName;
        if (class_exists($mapperClass)) {
            $this->mapper = new $mapperClass();
        }
    }

    public function pull(QueryFilter $query)
    {
        $action = new Action();
        $action->setHandled(true);
        try {
            $result = $this->pullData([], null, $query->getLimit());
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
            $action->setResult($this->deleteData($data));
        } catch (\Exception $exc) {
            Logger::write(ExceptionFormatter::format($exc), Logger::WARNING, 'controller');
            $err = new Error();
            $err->setCode($exc->getCode());
            $err->setMessage($exc->getFile() . ' (' . $exc->getLine() . '):' . $exc->getMessage());
            $action->setError($err);
        }
        return $action;
    }

    protected function pullDataDefault($data, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data, $limit);
        $result = $this->database->query($query);
        foreach ($result as $row) {
            $host = $this->mapper->toHost($row);
            $return[] = $host;
        }
        return $return;
    }

    protected function pushDataI18n($data, &$model, $key)
    {
        if (!method_exists($data, 'getI18ns')) {
            return;
        }
        $model[$key] = [];
        foreach ((array)$data->getI18ns() as $i18n) {
            $languageId = $this->utils->getLanguageId($i18n->getLanguageISO());
            if ($languageId !== false) {
                $endpoint = $this->mapper->toEndpoint($i18n);
                $model[$key][intval($languageId)] = $endpoint;
            }
        }
    }

    /**
     * Called on a pull on the main model controllers including their sub model controllers.
     *
     * @param $data  array  For sub models their parent models data.
     * @param $model object For sub models their parent model.
     * @param $limit int    The limit.
     * @return array A list of models resulting from the pull query.
     */
    public abstract function pullData(array $data, $model, $limit = null);

    /**
     * Just return the query for the the pulling of data.
     *
     * @param $data  array The data.
     * @param $limit int   The limit.
     * @return string The query.
     */
    protected abstract function pullQuery($data, $limit = null);
}