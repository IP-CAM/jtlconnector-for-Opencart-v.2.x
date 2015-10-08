<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
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
use jtl\Connector\Result\Action;

class Connector extends Controller
{
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
            'Specific'
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
                    Logger::write(ExceptionFormatter::format($exc), Logger::WARNING, 'controller');
                    $err = new Error();
                    $err->setCode($exc->getCode());
                    $err->setMessage($exc->getMessage());
                    $action->setError($err);
                }
            }
        }
        $action->setResult($results);
        return $action;
    }

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
        $version = file_get_contents(CONNECTOR_DIR . '/version');
        $identification = new ConnectorIdentification();
        $identification->setEndpointVersion($version)
            ->setPlatformName('OpenCart')
            ->setPlatformVersion(OpenCart::getInstance()->getVersion())
            ->setProtocolVersion(Application()->getProtocolVersion())
            ->setServerInfo($serverInfo);
        $action->setResult($identification);
        return $action;
    }

    public function finish()
    {
        $action = new Action();
        $action->setHandled(true);
        $action->setResult(true);
        return $action;
    }
}
