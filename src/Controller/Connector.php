<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Core\Logger\Logger;
use jtl\Connector\Core\Model\QueryFilter;
use jtl\Connector\Formatter\ExceptionFormatter;
use jtl\Connector\Model\ConnectorIdentification;
use jtl\Connector\OpenCart\Utility\Mmc;
use jtl\Connector\Result\Action;

class Connector extends BaseController
{
    /**
     * Statistic
     *
     * @param \jtl\Connector\Core\Model\QueryFilter $queryFilter
     * @return \jtl\Connector\Result\Action
     */
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
            'DeliveryNote',
            'Image',
            'Product',
            'Manufacturer',
            'Payment'
        ];

        foreach ($mainControllers as $mainController) {
            try {
                $controller = Mmc::getController($mainController);
                $result = $controller->statistic($queryFilter);
                if ($result !== null && $result->isHandled() && !$result->isError()) {
                    $results[] = $result->getResult();
                }
            } catch (\Exception $exc) {
                Logger::write(ExceptionFormatter::format($exc), Logger::WARNING, 'controller');
            }
        }

        $action->setResult($results);

        return $action;
    }

    /**
     * Identify
     *
     * @return \jtl\Connector\Result\Action
     */
    public function identify()
    {
        $action = new Action();
        $action->setHandled(true);

        $identification = new ConnectorIdentification();
        $identification->setEndpointVersion('1.0.0')
            ->setPlatformName('OpenCart')
            ->setPlatformVersion('1.0')
            ->setProtocolVersion(Application()->getProtocolVersion());

        $action->setResult($identification);

        return $action;
    }

    /**
     * Finish
     *
     * @return \jtl\Connector\Result\Action
     */
    public function finish()
    {
        $action = new Action();

        $action->setHandled(true);
        $action->setResult(true);

        return $action;
    }

    protected function pullQuery($data, $limit = null)
    {
        // TODO: Implement pullQuery() method.
    }

    /**
     * Called on a pull on the main model controllers including their sub model controllers.
     *
     * @param $data  array  For sub models their parent models data.
     * @param $model object For sub models their parent model.
     * @param $limit int    The limit.
     * @return array A list of models resulting from the pull query.
     */
    public function pullData($data, $model, $limit = null)
    {
        // TODO: Implement pullData() method.
    }

    protected function pushData($data, $model)
    {
        // TODO: Implement pushData() method.
    }

    protected function deleteData($data, $model)
    {
        // TODO: Implement deleteData() method.
    }

    /**
     * Called on the specific controller in order to get the availability of the model.
     *
     * @return string|int The availability of the model.
     */
    protected function getStats()
    {
        // TODO: Implement getStats() method.
    }
}
