<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @author Daniel Böhmer <daniel.boehmer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Core\Controller\Controller;
use jtl\Connector\Core\Logger\Logger;
use jtl\Connector\Core\Model\QueryFilter;
use jtl\Connector\Core\Rpc\Error;
use jtl\Connector\Formatter\ExceptionFormatter;
use jtl\Connector\Model\ConnectorIdentification;
use jtl\Connector\Model\ConnectorServerInfo;
use jtl\Connector\OpenCart\Utility\Constants;
use jtl\Connector\OpenCart\Utility\OpenCart;
use jtl\Connector\OpenCart\Utility\Option;
use jtl\Connector\Result\Action;

class Connector extends Controller
{
    public function identify()
    {
        $action = new Action();
        $action->setHandled(true);
        $returnMegaBytes = function ($value) {
            $value = trim($value);
            $unit = strtolower($value[strlen($value) - 1]);
            switch ($unit) {
                case 'g':
                    $value *= 1024;
            }
            return (int)$value;
        };
        $serverInfo = new ConnectorServerInfo();
        $serverInfo->setMemoryLimit($returnMegaBytes(ini_get('memory_limit')))
            ->setExecutionTime((int)ini_get('max_execution_time'))
            ->setPostMaxSize($returnMegaBytes(ini_get('post_max_size')))
            ->setUploadMaxFilesize($returnMegaBytes(ini_get('upload_max_filesize')));
        $identification = new ConnectorIdentification();
        $identification->setPlatformName('OpenCart')
            ->setPlatformVersion(OpenCart::getInstance()->getVersion())
            ->setEndpointVersion(OpenCart::getInstance()->getConnectorVersion())
            ->setProtocolVersion(Application()->getProtocolVersion())
            ->setServerInfo($serverInfo);
        $action->setResult($identification);
        return $action;
    }

    public function finish()
    {
        $action = new Action();
        $action->setHandled(true);
        try {
            $optionHelper = Option::getInstance();
            $optionHelper->deleteObsoleteOptions();
        } catch (\Exception $exc) {
            $this->handleException($exc, $action);
        }
        $action->setResult(true);
        return $action;
    }

    public function statistic(QueryFilter $queryFilter)
    {
        $action = new Action();
        $action->setHandled(true);
        $results = [];
        $mainControllers = [
            'Category',
            'Customer',
            'CustomerOrder',
            'CrossSelling',
            'Image',
            'Product',
            'Manufacturer',
            'Specific',
            'Payment'
        ];
        foreach ($mainControllers as $mainController) {
            $class = Constants::CONTROLLER_NAMESPACE . $mainController;
            if (class_exists($class)) {
                try {
                    $controllerObj = new $class();
                    $result = $controllerObj->statistic($queryFilter);
                    if ($result !== null && $result->isHandled() && !$result->isError()) {
                        $results[] = $result->getResult();
                    }
                } catch (\Exception $exc) {
                    $this->handleException($exc, $action);
                }
            }
        }
        $action->setResult($results);
        return $action;
    }

    /**
     * This method has to be called if an exception occured in one of the actions.
     * At first the exception is logged. In the second step an error object is builded and passed to the action which
     * is returned to the host.
     *
     * @param \Exception $exc The catched exception.
     * @param Action $action The action for which the rror has to be set.
     */
    protected function handleException($exc, &$action)
    {
        Logger::write(ExceptionFormatter::format($exc), Logger::WARNING, 'controller');
        $err = new Error();
        $err->setCode($exc->getCode());
        $err->setMessage($exc->getFile() . ' (' . $exc->getLine() . '):' . $exc->getMessage());
        $action->setError($err);
    }
}
