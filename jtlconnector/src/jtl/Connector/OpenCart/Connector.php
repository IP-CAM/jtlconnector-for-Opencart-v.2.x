<?php

namespace jtl\Connector\OpenCart;

use jtl\Connector\Base\Connector as BaseConnector;
use jtl\Connector\Core\Controller\Controller as CoreController;
use jtl\Connector\Core\Rpc\Method;
use jtl\Connector\Core\Rpc\RequestPacket;
use jtl\Connector\Core\Utilities\RpcMethod;
use jtl\Connector\OpenCart\Authentication\TokenLoader;
use jtl\Connector\OpenCart\Checksum\ChecksumLoader;
use jtl\Connector\OpenCart\Mapper\GlobalData\GlobalData;
use jtl\Connector\OpenCart\Mapper\PrimaryKeyMapper;
use jtl\Connector\OpenCart\Utility\Constants;
use jtl\Connector\Result\Action;

class Connector extends BaseConnector
{
    /**
     * Current Controller
     *
     * @var \jtl\Connector\Core\Controller\Controller
     */
    protected $controller;
    protected $action;

    public function initialize()
    {
        $this->setPrimaryKeyMapper(new PrimaryKeyMapper())
            ->setTokenLoader(new TokenLoader())
            ->setChecksumLoader(new ChecksumLoader());
    }

    public function canHandle()
    {
        $controller = RpcMethod::buildController($this->getMethod()->getController());
        if ($this->startsWith($controller, 'Product')) {
            $controller = 'Product\\' . $controller;
        } elseif (strpos($controller, 'Order') !== false) {
            $controller = 'Order\\' . $controller;
        } elseif (in_array($controller, array_merge(['GlobalData'], GlobalData::getModels()))) {
            $controller = 'GlobalData\\' . $controller;
        } elseif (strpos($controller, 'Specific') !== false) {
            $controller = 'Specific\\' . $controller;
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

    public function handle(RequestPacket $requestpacket)
    {
        $this->controller->setMethod($this->getMethod());

        if ($this->action === Method::ACTION_PUSH || $this->action === Method::ACTION_DELETE) {

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
                    ->setError($result->getError());
            }

            return $action;
        }
        return $this->controller->{$this->action}($requestpacket->getParams());
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController(CoreController $controller)
    {
        $this->controller = $controller;
        return $this;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
}
