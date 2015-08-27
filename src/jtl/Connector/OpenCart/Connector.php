<?php

namespace jtl\Connector\OpenCart;

use jtl\Connector\Base\Connector as BaseConnector;
use jtl\Connector\Core\Controller\Controller as CoreController;
use jtl\Connector\Core\Rpc\Method;
use jtl\Connector\Core\Rpc\RequestPacket;
use jtl\Connector\Core\Utilities\RpcMethod;
use jtl\Connector\OpenCart\Authentication\TokenLoader;
use jtl\Connector\OpenCart\Checksum\ChecksumLoader;
use jtl\Connector\OpenCart\Mapper\PrimaryKeyMapper;
use jtl\Connector\OpenCart\Utility\Constants;
use jtl\Connector\Result\Action;

/**
 * OpenCart Connector
 *
 * @access public
 */
class Connector extends BaseConnector
{
    /**
     * Current Controller
     *
     * @var \jtl\Connector\Core\Controller\Controller
     */
    protected $controller;

    /**
     * @var string
     */
    protected $action;

    public function initialize()
    {
        $this->setPrimaryKeyMapper(new PrimaryKeyMapper())
            ->setTokenLoader(new TokenLoader())
            ->setChecksumLoader(new ChecksumLoader());
    }

    /**
     * (non-PHPdoc)
     *
     * @see \jtl\Connector\Application\IEndpointConnector::canHandle()
     */
    public function canHandle()
    {
        $controller = RpcMethod::buildController($this->getMethod()->getController());
        if ($this->startsWith($controller, 'Product')) {
            $controller = 'Product' . DIRECTORY_SEPARATOR . $controller;
        } elseif (strpos($controller, 'Order') !== false) {
            $controller = 'Order' . DIRECTORY_SEPARATOR . $controller;
        }
        $class = Constants::CONTROLLER_NAMESPACE . $controller;
        if (class_exists($class)) {
            $this->controller = $class::getInstance();
            $this->action = RpcMethod::buildAction($this->getMethod()->getAction());
            return is_callable(array($this->controller, $this->action));
        }
        return false;
    }

    private function startsWith($haystack, $needle)
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \jtl\Connector\Application\IEndpointConnector::handle()
     */
    public function handle(RequestPacket $requestpacket)
    {
        $config = $this->getConfig();

        // Set the config to our controller
        $this->controller->setConfig($config);

        // Set the method to our controller
        $this->controller->setMethod($this->getMethod());

        if ($this->action === Method::ACTION_PUSH || $this->action === Method::ACTION_DELETE) {
            if ($this->action === Method::ACTION_PUSH && $this->getMethod()->getController() === 'image') {
                return $this->controller->{$this->action}($requestpacket->getParams());
            }

//            if ($this->action === Method::ACTION_PUSH && $this->getMethod()->getController() === 'product_price') {
//                $params = $requestpacket->getParams();
//                $result = $this->controller->update($params);
//                $results[] = $result->getResult();
//            } else {
//                foreach ($requestpacket->getParams() as $param) {
//                    $result = $this->controller->{$this->action}($param);
//                    $results[] = $result->getResult();
//                }
//            }

            if (!is_array($requestpacket->getParams())) {
                throw new \Exception("Expecting request array, invalid data given");
            }

            $action = new Action();
            $results = [];
            $entities = $requestpacket->getParams();
            foreach ($entities as $entity) {
                $result = $this->controller->{$this->action}($entity);

                if ($result->getResult() !== null) {
                    $results[] = $result->getResult();
                }

                $action->setHandled(true)
                    ->setResult($results)
                    ->setError($result->getError());    // Todo: refactor to array of errors
            }

            return $action;
        } else {
            return $this->controller->{$this->action}($requestpacket->getParams());
        }
    }

    /**
     * Getter Controller
     *
     * @return \jtl\Connector\Core\Controller\Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Setter Controller
     *
     * @param \jtl\Connector\Core\Controller\Controller $controller
     */
    public function setController(CoreController $controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * Getter Action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Setter Action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
}
