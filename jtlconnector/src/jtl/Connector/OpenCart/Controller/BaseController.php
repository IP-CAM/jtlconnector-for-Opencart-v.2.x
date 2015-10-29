<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @author Daniel Hoffmann <daniel.hoffmann@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
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
    protected $oc;
    protected $utils;
    protected $mapper;
    protected $database;
    protected $controllerName;

    /**
     * BaseController constructor.
     *
     * @param null $subClass Test classes chave to specify their parent class as they should not be used to build the
     * controller and mapper.
     */
    public function __construct($subClass = null)
    {
        $this->utils = Utils::getInstance();
        $reflect = (is_null($subClass)) ? new \ReflectionClass($this) : new \ReflectionClass($subClass);
        $shortName = $reflect->getShortName();
        $this->controllerName = $shortName;
        $mapperClass = str_replace('Controller', 'Mapper', $reflect->getNamespaceName()) . '\\' . $shortName;
        if (class_exists($mapperClass)) {
            $this->mapper = new $mapperClass();
        }
    }

    /**
     * Extracted method which can be overwritten by the tests to use another database or manipulate the OpenCart helper.
     */
    protected function initHelper()
    {
        $this->database = Db::getInstance();
        $this->oc = OpenCart::getInstance();
    }

    /**
     * Method called on a pull request.
     *
     * @param QueryFilter $query Filter data like the limit.
     *
     * @return Action The action which is handled by the core.
     */
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

    /**
     * Method called on a push request.
     *
     * @param DataModel $data The data of the object which should be saved.
     *
     * @return Action The action which will be handled by the core.
     */
    public function push(DataModel $data)
    {
        $action = new Action();
        $action->setHandled(true);
        try {
            $result = null;
            if (method_exists($this, 'pushData')) {
                $result = $this->pushData($data, null);
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

    /**
     * Method called on a delete request.
     *
     * @param DataModel $data The data of the object which should be deleted.
     *
     * @return Action The action which will be handled by the core.
     */
    public function delete(DataModel $data)
    {
        $action = new Action();
        $action->setHandled(true);
        try {
            $result = null;
            if (method_exists($this, 'deleteData')) {
                $action->setResult($this->deleteData($data));
            }
        } catch (\Exception $exc) {
            Logger::write(ExceptionFormatter::format($exc), Logger::WARNING, 'controller');
            $err = new Error();
            $err->setCode($exc->getCode());
            $err->setMessage($exc->getFile() . ' (' . $exc->getLine() . '):' . $exc->getMessage());
            $action->setError($err);
        }
        return $action;
    }

    /**
     * Default implementation for pulling data.
     * First an abstract method which returns the query is used to get all data.
     * At second the result is iterated. Foreach entry the mapper is called.
     *
     * @param array $data For sub models their parent models data.
     * @param integer $limit The limit which will be null for sub models.
     *
     * @return array The builded models to be returned to the host.
     */
    protected function pullDataDefault(array $data, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data, $limit);
        $result = $this->database->query($query);
        foreach ((array)$result as $row) {
            if ($this->mapper instanceof BaseMapper) {
                $host = $this->mapper->toHost($row);
                $return[] = $host;
            }
        }
        return $return;
    }

    /**
     * Default implementation for models with i18ns.
     * Based on the language ISO the OpenCart language id is caught and used to build the internatiolized array.
     * The information is directly appended to the model.
     *
     * @param DataModel $data The pushed model.
     * @param array $model The endpoint object where the i18ns should be added to.
     * @param string $key The key which should be used for the i18ns.
     */
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
     * @param array $data For sub models their parent models data.
     * @param DataModel $model For sub models their parent model.
     * @param integer $limit The limit which will be null for sub models.
     *
     * @return array A list of models resulting from the pull query.
     */
    public abstract function pullData(array $data, $model, $limit = null);

    /**
     * Just return the query for the the pulling of data.
     *
     * @param array $data For sub models their parent models data.
     * @param int $limit The limit which will be null for sub models.
     *
     * @return string The query to be executed to fetch all needed data.
     */
    protected abstract function pullQuery(array $data, $limit = null);
}